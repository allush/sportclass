<?php

if ($_SERVER['REQUEST_METHOD'] != "GET")
    return;

session_start();
include '../common/CUser.php';
include '../common/connect.php';
include 'wlog.php';
$user = new CUser($_SESSION['id_user']);
if (!$user->has_permission(array(array("3" => array("write" => "1", "read" => "1"))))) {
    if (isset($_GET['ajax'])) {
        die("Ошибка доступа");
    }
    header("Location: index.php?p=permission_denied");
    return;
}

if (!isset($_GET['id_product']))
    return;

$id_product = (int)$_GET['id_product'];

mysql_query("INSERT INTO `price_tag`(`id_product`) VALUES ('$id_product')");

if (isset($_GET['ajax'])) {
    if (-1 == mysql_affected_rows())
        die("Ценник для этого товара уже есть в списке");
    else
        die("Ценник успешно добавлен");
}

header("Location: " . $_SERVER['HTTP_REFERER']);
?>
