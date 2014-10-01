<?php

session_start();
include_once '../common/connect.php';
include_once '../common/CUser.php';
include_once 'CArticle_be.php';
include_once 'wlog.php';
$user = new CUser($_SESSION['id_user']);
if (!$user->has_permission(array(array("13" => array("write" => "1", "read" => "1"))))) {
    header("Location: index.php?p=permission_denied");
    return;
}

if (!isset($_POST['title']))
    return;

if (!isset($_POST['body']))
    return;

if (!isset($_POST['id_heading']))
    return;


CArticle_be::create($_POST['title'],$_POST['body'],$_POST['id_heading'] );

header("Location: index.php?p=articles ");
?>
