<?php

session_start();
include '../common/CUser.php';
include '../common/connect.php';
include 'CProduct_be.php';
include 'wlog.php';
$user = new CUser($_SESSION['id_user']);
if (!$user->has_permission(array(array("3" => array("write" => "1", "read" => "1"))))) {
    if (isset($_POST['ajax'])) {
        echo -1;
        return;
    }
    header("Location: index.php?p=permission_denied");
    return;
}

$id_product = $_POST['id_product'];
$id_catalog = $_POST['id_catalog'];
$name = htmlspecialchars($_POST['name'], ENT_QUOTES);
$description = htmlspecialchars($_POST['description'], ENT_QUOTES);
$cost = $_POST['cost'];
$existence = $_POST['existence'];
$id_product_unit = $_POST['id_product_unit'];
$discount = $_POST['discount'];
$visible = $_POST['visible'];

$product = new CProduct_be($id_product);
$product->set_cost($cost);
$product->set_name($name);
$product->set_description($description);
$product->set_discount($discount);
$product->set_id_product_unit($id_product_unit);
$product->set_existence($existence);
$product->set_visible($visible);


if (!mysql_query("DELETE FROM `product_catalog` WHERE `id_product`='$id_product' "))
    return;

if (is_array($id_catalog)) {
    foreach ($id_catalog as $id_cat)
        $product->set_product_catalog($id_cat);
}
if (isset($_POST['ajax'])) {
    echo 1;
    return;
}

header("Location: " . $_SERVER['HTTP_REFERER'] . "#" . $id_product);
?>

