<?php

session_start();
include '../common/connect.php';
include '../common/CUser.php';
include '../common/CCatalog.php';
include 'wlog.php';

$user = new CUser($_SESSION['id_user']);
if (!$user->has_permission(array(array("2" => array("write" => "1", "read" => "1"))))) {
    header("Location: index.php?p=permission_denied");
    return;
}

if (!isset($_GET['id']))
    return;
$id_catalog = $_GET['id'];
settype($id_catalog, "int");
$catalog = new CCatalog($id_catalog);
$catalog->delete();

header("Location: " . $_SERVER['HTTP_REFERER']);
?>
