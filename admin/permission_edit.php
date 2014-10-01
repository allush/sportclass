<?php

session_start();
include '../common/connect.php';
include '../common/CUser.php';
include 'wlog.php';
$user = new CUser($_SESSION['id_user']);
if (!$user->has_permission(array(array("1" => array("write" => "1", "read" => "1"))))) {
    header("Location: index.php?p=permission_denied");
    return;
}

if (!isset($_POST['id_permission']))
    return;
if(!is_numeric($_POST['id_permission']))
    return;

$id_permission = $_POST['id_permission'];

if (!isset($_POST['name']))
    return;
$name = $_POST['name'];

$referer = $_SERVER['HTTP_REFERER'];
if (isset($_POST['referer']))
    $referer = $_POST['referer'];

$permission = new CPermission($id_permission);
$permission->set_name($name);

header("Location: " . $referer);
?>
