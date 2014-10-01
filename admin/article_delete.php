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

if (!isset($_GET['id_article']) || !is_numeric($_GET['id_article']))
    return;

$article = new CArticle_be($_GET['id_article']);
$article->delete();

header("Location: " . $_SERVER['HTTP_REFERER']);
?>