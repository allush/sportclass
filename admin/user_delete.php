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

if (!isset($_GET['id_user']))
    return;

$id_user = $_GET['id_user'];
settype($id_user, "int");

$user = new CUser($id_user);
$user->delete();

header("Location: " . $_SERVER['HTTP_REFERER']);
?>