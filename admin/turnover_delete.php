<?php

session_start();
include '../common/connect.php';
include '../common/CTurnover.php';
include '../common/CUser.php';
include 'CProduct_be.php';
include 'wlog.php';

if (!isset($_GET['id_turnover']))
    return;
if (!is_numeric($_GET['id_turnover']))
    return;
$id_turnover = $_GET['id_turnover'];
settype($id_turnover, "int");


if (!isset($_GET['id_turnover_type']))
    return;
if (!is_numeric($_GET['id_turnover_type']))
    return;
$id_turnover_type = $_GET['id_turnover_type'];
settype($id_turnover_type, "int");

$user = new CUser($_SESSION['id_user']);
$key = 0;
switch ($id_turnover_type) {
    case 1:
        $turnover = new CIncome($id_turnover);
        $key = $user->has_permission(array(array("4" => array("write" => "1", "read" => "1"))));
        break;

    case 2:
        $turnover = new CAllowance($id_turnover);
        $key = $user->has_permission(array(array("5" => array("write" => "1", "read" => "1"))));
        break;

    case 3:
        $turnover = new CSale($id_turnover);
        $key = $user->has_permission(array(array("6" => array("write" => "1", "read" => "1"))));
        break;
}

if (!$key) {
    header("Location: index.php?p=permission_denied");
    return;
}

$turnover->delete();

header("Location: " . $_SERVER['HTTP_REFERER']);
?>