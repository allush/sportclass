<?php

session_start();
include '../common/CUser.php';
include '../common/connect.php';
include 'CVideo_be.php';
include 'wlog.php';

$user = new CUser($_SESSION['id_user']);
if (!$user->has_permission(array(array("8" => array("write" => "1", "read" => "1"))))) {
    header("Location: index.php?p=permission_denied");
    return;
}

if (!isset($_GET['code']))
    return;

$code = $_GET['code'];

$video = new CVideo_be($code);
$video->delete();

header("Location: " . $_SERVER['HTTP_REFERER']);
?>