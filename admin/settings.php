<?php

if ($_SERVER['REQUEST_METHOD'] != "POST")
    return;

session_start();
include '../common/connect.php';
include '../common/CUser.php';
include 'wlog.php';

$user = new CUser($_SESSION['id_user']);
if (!$user->has_permission(array(array("7" => array("write" => "1", "read" => "1"))))) {
    header("Location: index.php?p=permission_denied");
    return;
}



header("Location: index.php?p=settings");
?>
