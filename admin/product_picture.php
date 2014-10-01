<?php

session_start();
include '../common/CUser.php';
include '../common/connect.php';
include 'CProduct_be.php';
include 'wlog.php';
$user = new CUser($_SESSION['id_user']);
if (!$user->has_permission(array(array("3" => array("write" => "1", "read" => "1"))))) {
    header("Location: index.php?p=permission_denied");
    return;
}

if (!is_numeric($_POST['id_product']))
    return;
$id_product = $_POST['id_product'];

if (!is_numeric($_POST['id_product_picture']))
    return;
$id_product_picture = $_POST['id_product_picture'];

$action = $_POST['action'];

$product = new CProduct_be($id_product);

switch ($action) {
    case "delete":
        $product->delete_picture($id_product_picture);
        break;

    case "cover":
        $product->set_cover($id_product_picture);
        break;
}
if(isset($_POST['ajax']))
    echo 1;
else
    header("Location: " . $_POST['referer']."#".$id_product);
?>
