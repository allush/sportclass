<?php

session_start();
include '../common/connect.php';
include '../common/CUser.php';
include 'wlog.php';
$user = new CUser($_SESSION['id_user']);
if (!$user->has_permission(array(array("1" => array("write" => "1", "read" => "1"))))) {
    header("Location: index.php?p=permission_denied");
    return;
}

if (!isset($_GET['id_permission']) || !is_numeric($_GET['id_permission']))
    return;

$id_permission = (int)$_GET['id_permission'];

$permission = new CPermission($id_permission);
$permission->delete();

header("Location: " . $_SERVER['HTTP_REFERER']);
?>