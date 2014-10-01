<?php

if ($_SERVER['REQUEST_METHOD'] != "POST")
    return;

if (!isset($_POST['auth']))
    return;

session_start();

include '../common/connect.php';
include '../common/CUser.php';
include 'wlog.php';

if (!isset($_POST['email']))
    return;
$email = $_POST['email'];
$email = htmlspecialchars($email);

if (!isset($_POST['password']))
    return;
$password = $_POST['password'];
$password = htmlspecialchars($password);

$result = mysql_query("SELECT * FROM `user` WHERE `deleted`='0' AND `email`='$email' AND `password`='" . md5($password) . "'") or die(mysql_error());
$num = mysql_num_rows($result);

if ($num) {
    $row = mysql_fetch_array($result);
    $_SESSION['id_user'] = $row['id_user'];
    
    $user = new CUser($_SESSION['id_user']);
    $user->enter();    
    header("Location: index.php");
    return;
}
wlog(-1, "Ошибка входа c параметрами email= ".$email."; password=".$password);
header("Location: _auth.php");
?>
