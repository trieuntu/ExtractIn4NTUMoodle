<?php
require 'extract.php';
require 'vendor/autoload.php'; // PhpSpreadsheet

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

if (!isset($_FILES['htmlfile']) || $_FILES['htmlfile']['error'] !== UPLOAD_ERR_OK) {
    die("File upload lỗi.");
}

$tmpPath = $_FILES['htmlfile']['tmp_name'];
$format = $_POST['format'] ?? 'csv';

// Trích xuất dữ liệu
$data = extractUserAndEmail($tmpPath);

if (empty($data)) {
    die("Không tìm thấy dữ liệu Username và Email.");
}

$filename = 'output_' . time();

if ($format === 'xlsx') {
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->fromArray(['Username', 'Full Name', 'Email'], NULL, 'A1');
    $sheet->fromArray($data, NULL, 'A2');
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header("Content-Disposition: attachment; filename=\"$filename.xlsx\"");
    $writer = new Xlsx($spreadsheet);
    $writer->save("php://output");
} else {
    header('Content-Type: text/csv');
    header("Content-Disposition: attachment; filename=\"$filename.csv\"");
    $out = fopen('php://output', 'w');
    fputcsv($out, ['Username', 'Full Name', 'Email']);
    foreach ($data as $row) {
        fputcsv($out, $row);
    }
    fclose($out);
}

// Tự xoá file upload tạm (đã nằm ở tmp, nhưng đảm bảo sạch bộ nhớ)
unlink($tmpPath);
exit;
