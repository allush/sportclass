<?php

session_start();
include '../common/connect.php';
include '../common/CUser.php';
include '../common/CTurnover.php';
include 'CProduct_be.php';
include 'wlog.php';

if (!isset($_GET['id_turnover_item']))
    return;
if (!is_numeric($_GET['id_turnover_item']))
    return;
$id_turnover_item = $_GET['id_turnover_item'];
settype($id_turnover_item, "int");

if (!isset($_GET['id_turnover_type']))
    return;
if (!is_numeric($_GET['id_turnover_type']))
    return;
$id_turnover_type = $_GET['id_turnover_type'];
settype($id_turnover_type, "int");

$turnover_item = new CTurnover_item($id_turnover_item);

$key = 0;
$user = new CUser($_SESSION['id_user']);
switch ($id_turnover_type) {
    case 1:
        $turnover = new CIncome($turnover_item->id_turnover());
        $key = $user->has_permission(array(array("4" => array("write" => "1", "read" => "1"))));
        break;

    case 2:
        $turnover = new CAllowance($turnover_item->id_turnover());
        $key = $user->has_permission(array(array("5" => array("write" => "1", "read" => "1"))));
        break;

    case 3:
        $turnover = new CSale($turnover_item->id_turnover());
        $key = $user->has_permission(array(array("6" => array("write" => "1", "read" => "1"))));
        break;
}

if (!$key) {
    header("Location: index.php?p=permission_denied");
    return;
}
$turnover->delete_item($turnover_item->id_turnover_item());

header("Location: " . $_SERVER['HTTP_REFERER']);
?>