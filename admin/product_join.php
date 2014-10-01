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

$ids = $_POST['id'];

$product = CProduct_be::join($ids);

if (isset($_POST['ajax'])) {
    $product->show();
    return;
}

header("Location: " . $_SERVER['HTTP_REFERER']);
?>

