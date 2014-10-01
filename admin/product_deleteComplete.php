<?php

session_start();
include '../common/CUser.php';
include '../common/connect.php';
include 'CProduct_be.php';
include 'wlog.php';
$user = new CUser($_SESSION['id_user']);
if (!$user->has_permission(array(array("3" => array("write" => "1", "read" => "1"))))) {
    if (isset($_GET['ajax']))
        die(-1);
    header("Location: index.php?p=permission_denied");
    return;
}

if (!isset($_GET['id_product']) || !is_numeric($_GET['id_product']))
    return;

$id_product = (int)$_GET['id_product'];

$product = new CProduct_be($id_product);
$product->deleteComplete();

if (isset($_GET['ajax'])) {
    echo 1;
    return;
}

header("Location: " . $_SERVER['HTTP_REFERER']);
?>