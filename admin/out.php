<?php
ini_set('display_errors',1);
error_reporting(E_ALL|E_STRICT);

session_start();
include '../common/connect.php';
include '../common/CUser.php';
include 'wlog.php';

$user = new CUser($_SESSION['id_user']);
$user->out();

header("Location: _auth.php");
?>