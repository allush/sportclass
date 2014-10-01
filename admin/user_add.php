<?php

session_start();
include '../common/CUser.php';
include '../common/connect.php';
include 'wlog.php';
$user = new CUser($_SESSION['id_user']);
if (!$user->has_permission(array(array("1" => array("write" => "1", "read" => "1"))))) {
    header("Location: index.php?p=permission_denied");
    return;
}

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

if (!isset($_POST['password']))
    return;
$password = $_POST['password'];

if (!isset($_POST['id_role']))
    return;
$id_role = $_POST['id_role'];

$referer = $_SERVER['HTTP_REFERER'];
if (isset($_POST['referer']))
    $referer = $_POST['referer'];

mysql_query("INSERT INTO `user`(`first_name`,`last_name`,`email`,`phone`, `password`) VALUES('$first_name', '$last_name', '$email', '$phone', '" . md5($password) . "')") or die(mysql_error());

//mysql_query("DELETE FROM `user_role` WHERE `id_user`=(SELECT MAX(`id_user`) FROM `user`)") or die(mysql_error());

for ($i = 0; $i < sizeof($id_role); $i++) {
    mysql_query("INSERT INTO `user_role`( `id_user`, `id_role` ) VALUES( (SELECT MAX(`id_user`) FROM `user`), '" . $id_role[$i] . "' )") or die(mysql_error());
}

header("Location: " . $referer);
?>
