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

if (!isset($_POST['name']))
    return;

$onMain = 0;
if (isset($_POST['onMain']))
    $onMain = 1;

$heading = CHeading::create($_POST['name']);
$heading->setOnMain($onMain);

header("Location: index.php?p=heading ");
?>
