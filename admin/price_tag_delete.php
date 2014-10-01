<?php

session_start();
include '../common/CUser.php';
include '../common/connect.php';
include 'CProduct_be.php';
include 'CPrice_tag.php';
include 'wlog.php';
$user = new CUser($_SESSION['id_user']);
if (!$user->has_permission(array(array("3" => array("write" => "1", "read" => "1"))))) {
    if (isset($_GET['ajax'])) {
        echo -1;
        return;
    }
    header("Location: index.php?p=permission_denied");
    return;
}

if (!isset($_GET['id_product']))
    return;

if (!is_numeric($_GET['id_product']))
    return;

$id_product = (int)$_GET['id_product'];

$price_tag = new CPrice_tag($id_product);
$price_tag->delete();

if (isset($_GET['ajax'])) {
    echo 1;
    return;
}

header("Location: " . $_SERVER['HTTP_REFERER']);
?>