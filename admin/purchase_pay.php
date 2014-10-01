<?php
/**
 * Установка флага confirmed =1 для всех пунктов текущей покупки
 */

include '../common/connect.php';
include 'CPurchase.php';
include '../common/CTurnover_item.php';

$purchase = new CPurchase();
$purchase->pay();

header("Location: ".$_SERVER['HTTP_REFERER']);
?>
