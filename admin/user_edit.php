<?php

error_reporting(E_ALL);
session_start();
include '../common/CUser.php';
include_once '../common/CMaster.php';
include '../common/connect.php';
include 'wlog.php';

$user = new CUser($_SESSION['id_user']);
if (!$user->has_permission(array(array("1" => array("write" => "1", "read" => "1"))))) {
    header("Location: index.php?p=permission_denied");
    return;
}

if (!isset($_POST['id_user']))
    return;
$id_user = $_POST['id_user'];

$user = new CMaster($id_user);

if (!isset($_POST['first_name']))
    return;
$first_name = $_POST['first_name'];

if (!isset($_POST['last_name']))
    return;
$last_name = $_POST['last_name'];

if (!isset($_POST['email']))
    return;
$email = $_POST['email'];

if (!isset($_POST['phone']))
    return;
$phone = $_POST['phone'];

if (!isset($_POST['id_role']))
    return;
$id_role = $_POST['id_role'];


if (isset($_FILES['photo']))
    $user->set_photo($_FILES['photo']);

if (isset($_POST['info']))
    $user->set_info($_POST['info']);

$user->set_first_name($first_name);
$user->set_last_name($last_name);
$user->set_email($email);
$user->set_phone($phone);
$user->set_roles($id_role);

$referer = $_SERVER['HTTP_REFERER'];
if (isset($_POST['referer']))
    $referer = $_POST['referer'];

header("Location: " . $referer);
?>
