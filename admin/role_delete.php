<?php

session_start();
include '../common/CUser.php';
include '../common/connect.php';
include 'wlog.php';
$user = new CUser($_SESSION['id_user']);
if (!$user->has_permission(array(array("1" => array("write" => "1", "read" => "1"))))) {
    header("Location: index.php?p=permission_denied");
    return;
}

if (!isset($_GET['id_role']))
    return;

if (!is_numeric($_GET['id_role']))
    return;

$id_role = $_GET['id_role'];
settype($id_role, "int");

$role = new CRole($id_role);
$role->delete();

header("Location: " . $_SERVER['HTTP_REFERER']);
?>