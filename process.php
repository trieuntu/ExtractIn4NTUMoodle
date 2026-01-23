<?php
require 'extract.php';
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Validate uploads
$hasExcel = isset($_FILES['excel']) && $_FILES['excel']['error'] === UPLOAD_ERR_OK;
$hasHtml = isset($_FILES['htmlfiles']) && is_array($_FILES['htmlfiles']['error']);

if (!$hasExcel) {
    die('Vui lòng tải lên file Excel gốc (.xls).');
}

$validHtmlCount = 0;
if ($hasHtml) {
    foreach ($_FILES['htmlfiles']['error'] as $err) {
        if ($err === UPLOAD_ERR_OK) { 
            $validHtmlCount++;
        }
    }
}

if ($validHtmlCount === 0) {
    die('Vui lòng chọn ít nhất một file HTML.');
}

$excelPath = $_FILES['excel']['tmp_name'];
$htmlFiles = $_FILES['htmlfiles'];

// Helper: find header row containing specific label
function findHeaderRow($sheet, $label, $maxRows = 20) {
    $highestColumn = $sheet->getHighestColumn();
    $highestIndex = Coordinate::columnIndexFromString($highestColumn);
    
    for ($row = 1; $row <= $maxRows; $row++) {
        for ($col = 1; $col <= $highestIndex; $col++) {
            $cell = Coordinate::stringFromColumnIndex($col) . $row;
            $value = trim((string) $sheet->getCell($cell)->getValue());
            if (strcasecmp($value, $label) === 0) {
                return $row;
            }
        }
    }
    return null;
}

// Helper: find column index by header label (case-insensitive match)
function findColumnIndex($sheet, $headerRow, $label) {
    $highestColumn = $sheet->getHighestColumn();
    $highestIndex = Coordinate::columnIndexFromString($highestColumn);
    for ($col = 1; $col <= $highestIndex; $col++) {
        $cell = Coordinate::stringFromColumnIndex($col) . $headerRow;
        $value = trim((string) $sheet->getCell($cell)->getValue());
        if (strcasecmp($value, $label) === 0) {
            return $col;
        }
    }
    return null;
}

// Helper: build a short, safe column title from filename
function makeColumnTitle($filename) {
    $base = pathinfo($filename, PATHINFO_FILENAME);
    $normalized = preg_replace('/[^a-zA-Z0-9_-]+/', '_', $base);
    $normalized = trim($normalized, '_');
    return substr($normalized === '' ? 'HTML' : $normalized, 0, 20);
}

// Load base Excel (suppress noisy libxml warnings from HTML-like .xls)
$libxmlPrev = libxml_use_internal_errors(true);
$spreadsheet = IOFactory::load($excelPath);
libxml_clear_errors();
libxml_use_internal_errors($libxmlPrev);
$sheet = $spreadsheet->getActiveSheet();

// Preserve original column widths; also remember how many original cols
$originalDimensions = [];
foreach ($sheet->getColumnDimensions() as $colLetter => $dimension) {
    $originalDimensions[$colLetter] = [
        'autoSize' => $dimension->getAutoSize(),
        'width' => $dimension->getWidth(),
    ];
}
$baseColCount = Coordinate::columnIndexFromString($sheet->getHighestColumn());

// Auto-detect header row by searching for "Mã SV" (for .xlsx compatibility)
$headerRow = findHeaderRow($sheet, 'Mã SV', 20);
if ($headerRow === null) {
    // Fallback to row 8 for legacy .xls files
    $headerRow = 8;
}

$masvCol = findColumnIndex($sheet, $headerRow, 'Mã SV');
if ($masvCol === null) {
    die('Không tìm thấy cột "Mã SV" ở dòng tiêu đề.');
}

$currentColIndex = Coordinate::columnIndexFromString($sheet->getHighestColumn());
$highestDataRow = $sheet->getHighestDataRow();

// Iterate uploaded HTML files
foreach ($htmlFiles['tmp_name'] as $idx => $tmpHtmlPath) {
    if ($htmlFiles['error'][$idx] !== UPLOAD_ERR_OK) {
        continue; // skip invalid uploads
    }

    $colTitle = makeColumnTitle($htmlFiles['name'][$idx]);
    $currentColIndex++;
    $headerCell = Coordinate::stringFromColumnIndex($currentColIndex) . $headerRow;
    $sheet->setCellValue($headerCell, $colTitle);

    $usernames = extractUsernamesFromHtml($tmpHtmlPath);
    $userLookup = array_flip($usernames);

    for ($row = $headerRow + 1; $row <= $highestDataRow; $row++) {
        $studentCell = Coordinate::stringFromColumnIndex($masvCol) . $row;
        $studentCode = trim((string) $sheet->getCell($studentCell)->getValue());
        if ($studentCode === '') {
            continue;
        }
        $score = isset($userLookup[$studentCode]) ? 10 : 0;
        $scoreCell = Coordinate::stringFromColumnIndex($currentColIndex) . $row;
        $sheet->setCellValue($scoreCell, $score);
    }
}

// Re-apply preserved widths for original columns; if width unknown, autosize to fit
$defaultWidth = $sheet->getDefaultColumnDimension()->getWidth();
for ($col = 1; $col <= $baseColCount; $col++) {
    $letter = Coordinate::stringFromColumnIndex($col);
    $dim = $sheet->getColumnDimension($letter);
    if (isset($originalDimensions[$letter])) {
        $info = $originalDimensions[$letter];
        // If explicit width existed, keep it and disable autosize to avoid shrink
        if ($info['width'] !== null && $info['width'] !== -1) {
            $dim->setAutoSize(false);
            $dim->setWidth($info['width']);
        } else {
            // No explicit width stored; let autosize widen to content
            $dim->setAutoSize(true);
        }
    } else {
        // Column had no custom dimension; autosize to avoid collapse
        $dim->setAutoSize(true);
        if ($defaultWidth !== null && $defaultWidth !== -1) {
            $dim->setWidth($defaultWidth);
        }
    }
}

$filename = 'ket_qua_' . time() . '.xlsx';
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment; filename=\"$filename\"");
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
