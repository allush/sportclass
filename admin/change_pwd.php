<?php

session_start();
include '../common/CUser.php';
include '../common/connect.php';
include 'wlog.php';

$user = new CUser($_SESSION['id_user']);
if (!$user->has_permission(array(array("9" => array("write" => "1", "read" => "1"))))) {    
    header("Location: index.php?p=permission_denied");
    return;
}

if (!isset($_POST['cur_password']))
    die("Не указан текущий пароль");

if (!isset($_POST['new_password']))
    die("Не указан новый пароль");


$cur_pwd = $_POST['cur_password'];
$new_pwd = $_POST['new_password'];

if (md5($cur_pwd) != $user->password())
    die("Вы ввели неверный пароль. Пароль не был изменен");

$user->set_password($new_pwd);

header("Location: index.php?p=my_profile");
?>

