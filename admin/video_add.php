<?php

session_start();
include '../common/CUser.php';
include '../common/connect.php';
include 'wlog.php';
$user = new CUser($_SESSION['id_user']);
if (!$user->has_permission(array(array("8" => array("write" => "1", "read" => "1"))))) {
    header("Location: index.php?p=permission_denied");
    return;
}

if (!isset($_POST['code']))
    return;
if (!isset($_POST['id_heading']))
    return;

include_once 'CVideo_be.php';

CVideo_be::create($_POST['code'],$_POST['id_heading']);

$referer = $_SERVER['HTTP_REFERER'];
if (isset($_POST['referer']))
    $referer = $_POST['referer'];

header("Location: " . $referer);
?>
