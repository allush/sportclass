<?php

session_start();
include 'common/connect.php';
include 'common/CUser.php';
include 'CProduct_fe.php';

if (!isset($_POST['keyword']) || empty($_POST['keyword']))
    return 0;
$keyword = trim($_POST['keyword']);

$result = mysql_query("SELECT * FROM `product` WHERE `name` LIKE '%$keyword%'");

if (!mysql_num_rows($result))
    return 0;

//Выводим товары
$i = 0;
while ($row = mysql_fetch_array($result)) {
    $product = new CProduct_fe($row['id_product']);
    $product->show(++$i);
}
?>
