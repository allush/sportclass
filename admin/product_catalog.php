<?php

session_start();
include '../common/connect.php';
include '../common/CUser.php';
include 'CProduct_be.php';
include 'wlog.php';
$user = new CUser($_SESSION['id_user']);
if (!$user->has_permission(array(array("3" => array("write" => "1", "read" => "1"))))) {
    header("Location: index.php?p=permission_denied");
    return;
}

if (!isset($_POST['id_product']))
    return;

if (!is_numeric($_POST['id_product']))
    return;

$id_product = $_POST['id_product'];
$id_catalog = $_POST['id_catalog'];

$product = new CProduct_be($id_product);

if (!mysql_query("DELETE FROM `product_catalog` WHERE `id_product`='$id_product' "))
    return;

for ($i = 0; $i < sizeof($id_catalog); $i++)
    $product->set_product_catalog($id_catalog[$i]);

header("Location: " . $_POST['referer'] . "#" . $id_product);
?>
