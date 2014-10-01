<?php

if ($_SERVER['REQUEST_METHOD'] != "POST")
    return;

$id_catalog = 0;
if (isset($_POST['id_catalog']))
    $id_catalog = (int) $_POST['id_catalog'];

$user = "";
if (isset($_POST['id_user']) && is_numeric($_POST['id_user'])) {
    $user = " AND `id_user`=" . $_POST['id_user'];
}


if (!isset($_POST['from']))
    return;
$from = (int) $_POST['from'];

if (!isset($_POST['part']))
    return;
$part = (int) $_POST['part'];


include 'common/connect.php';
include 'common/CCatalog.php';
include 'CProduct_fe.php';
include 'common/CUser.php';

$catalog = new CCatalog($id_catalog);

$children = array();
// Получить всех потомков данного каталога, чтобы выбрать все товары из них
$children = $catalog->children();
//добавить сам каталог в список, чтобы товары выбирались и из него тоже
array_push($children, $id_catalog);
$children = implode(",", $children);

// Выводим товары этого каталога и всех подкаталогов
$query = "
    SELECT 
    `id_product` 
    FROM 
    `product`  
    WHERE 
    `id_product` IN  ( SELECT `id_product` FROM `product_catalog` WHERE `id_catalog` IN ($children) GROUP BY `id_product`) AND 
    `visible`='1' AND `existence` >'0'  
    ORDER BY 
    `id_product` DESC
    LIMIT $from,$part";

$result = mysql_query($query) or die(mysql_error());
if (!$result)
    return;
$i = 1;
while ($row = mysql_fetch_array($result)) {
    $product = new CProduct_fe($row['id_product']);
    $product->show($i++);
}
?>
