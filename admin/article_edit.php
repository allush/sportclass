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

if (!isset($_POST['id_article']) || !is_numeric($_POST['id_article']))
    return;
if (!isset($_POST['title']))
    return;
if (!isset($_POST['body']))
    return;
if (!isset($_POST['id_heading']) || !is_numeric($_POST['id_heading']))
    return;


$article = new CArticle_be($_POST['id_article']);
$article->setTitle($_POST['title']);
$article->setBody($_POST['body']);
$article->setHeading($_POST['id_heading']);

header("Location: index.php?p=articles ");
?>
