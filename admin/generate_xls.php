<?php

error_reporting(E_ALL);

include '../common/connect.php';
include 'CProduct_be.php';
include '../common/CTurnover_item.php';

$result = mysql_query("SELECT `query` FROM `last_query`");

if (!$result)
    return;
$row = mysql_fetch_array($result);

if (!$row)
    return;

$query = base64_decode($row['query']);

include "phpexcel/PHPExcel.php";

$pExcel = new PHPExcel();
$pExcel->setActiveSheetIndex(0);
$aSheet = $pExcel->getActiveSheet();

$aSheet->setCellValue("A1", "Дата"); // Дата
$aSheet->setCellValue("B1", "Наименование"); // Наименование
$aSheet->setCellValue("C1", "Кол-во"); // Кол-во
$aSheet->setCellValue("D1", "Цена"); // Цена
$aSheet->setCellValue("E1", "Сумма"); // Сумма

$result = mysql_query($query);
if (!$result)
    return;

$i = 2;
//Перебираем все обороты указанного типа и в указанный период
while ($row = mysql_fetch_array($result)) {
    $result_i = mysql_query("SELECT * FROM `turnover_item` WHERE `id_turnover`='" . $row['id_turnover'] . "'");
    if (!$result_i)
        continue;

    // Перебираем все пункты каждого оборота
    while ($row_i = mysql_fetch_array($result_i)) {
        $item = new CTurnover_item($row_i['id_turnover_item']);
        $product = new CProduct_be($item->id_product());

        $aSheet->setCellValue("A" . $i, $row['date']); // Дата
        $aSheet->setCellValue("B" . $i, $product->name()); // Наименование
        $aSheet->setCellValue("C" . $i, $item->quantity()); // Кол-во
        $aSheet->setCellValue("D" . $i, $product->cost()); // Цена
        $aSheet->setCellValue("E" . $i, $product->cost() * $item->quantity()); // Сумма
        $i++;
    }
    $i++;
}

$name = "report.xlsx";
$path = "document/" . $name;

include "phpexcel/PHPExcel/Writer/Excel2007.php";
$objWriter = new PHPExcel_Writer_Excel2007($pExcel);
$objWriter->save($path);

if (file_exists($path)) {
    header('Content-Type: application/vnd.ms-excel2007');
    header('Content-Disposition: attachment;filename="' . $name . '"');
    header('Cache-Control: max-age=0');
    readfile($path);
}
?>
