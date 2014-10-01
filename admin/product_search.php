<?php

session_start();
include '../common/CUser.php';
include '../common/connect.php';
include 'CProduct_be.php';
include 'wlog.php';
$user = new CUser($_SESSION['id_user']);
if (!$user->has_permission(array(array("3" => array("write" => "0", "read" => "1"))))) {
    if (isset($_POST['ajax'])) {
        echo -1;
        return;
    }
    header("Location: index.php?p=permission_denied");
    return;
}

if (!isset($_POST['keyword']) || empty($_POST['keyword']))
    return 0;

$keyword = trim($_POST['keyword']);

$result = mysql_query("SELECT * FROM `product` WHERE `id_product`='$keyword' OR `name` LIKE '%$keyword%'");

if (!mysql_num_rows($result))
    return 0;

//Формируем список идентификаторов товаров
$ids = array();
$arr = "";
$i = 0;
while ($row = mysql_fetch_array($result)) {
    array_push($ids, $row['id_product']);
    if ($i++)
        $arr .= ",";
    $arr .= $row['id_product'];
}
$arr .= "";
echo $arr;

//разделитель
echo "<--!-->";

//Выводим товары
foreach ($ids as $id) {
    $product = new CProduct_be($id);
    $product->show();
}
?>
