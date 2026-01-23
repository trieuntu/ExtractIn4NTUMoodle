<?php
declare(strict_types=1);
require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

$file = './test/DS-cntt-3_Ngoan.xlsx';
$spreadsheet = IOFactory::load($file);
$sheet = $spreadsheet->getActiveSheet();
$rows = $sheet->toArray();

echo "Tổng số dòng: " . count($rows) . "\n\n";
echo str_repeat('=', 100) . "\n";

foreach (array_slice($rows, 0, 20) as $i => $row) {
    echo "Dòng " . ($i + 1) . ": ";
    echo implode(' | ', array_map(fn($v) => $v ?? '', $row));
    echo "\n";
}
?>