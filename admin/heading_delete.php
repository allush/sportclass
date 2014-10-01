<?php

session_start();
include_once '../common/connect.php';
include_once '../common/CUser.php';
include_once '../common/CHeading.php';
include_once 'wlog.php';

$user = new CUser($_SESSION['id_user']);
if (!$user->has_permission(array(array("14" => array("write" => "1", "read" => "1"))))) {
    header("Location: index.php?p=permission_denied");
    return;
}

if (!isset($_GET['id_heading']) || !is_numeric($_GET['id_heading']))
    return;

$heading = new CHeading($_GET['id_heading']);
$heading->delete();

header("Location: " . $_SERVER['HTTP_REFERER']);
?>