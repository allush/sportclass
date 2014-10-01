<?php

if ($_SERVER['REQUEST_METHOD'] != "POST")
    return;

session_start();
include '../common/connect.php';
include '../common/CUser.php';
include '../common/CTurnover.php';
include 'CProduct_be.php';
include 'wlog.php';


if (!isset($_POST['id_turnover_type']))
    return;
$id_turnover_type = $_POST['id_turnover_type'];

if (!isset($_POST['id_product']))
    return;
$id_product = $_POST['id_product'];

if (!isset($_POST['quantity']))
    return;
$quantity = $_POST['quantity'];

$id_user = $_SESSION['id_user'];
$user = new CUser($id_user);
$key = 0;
switch ($id_turnover_type) {
    case 1:
        $key = $user->has_permission(array(array("4" => array("write" => "1", "read" => "1"))));
        break;

    case 2:
        $key = $user->has_permission(array(array("5" => array("write" => "1", "read" => "1"))));
        break;

    case 3:
        $key = $user->has_permission(array(array("6" => array("write" => "1", "read" => "1"))));
        break;
}
if (!$key) {
//если обращение к скрипту не через ajax
    if (!isset($_POST['ajax']))
        header("Location: index.php?p=permission_denied");
    else
        echo -1;
    return;
}

switch ($id_turnover_type) {
    case 1:
        $turnover = new CIncome();
        $turnover->create($id_turnover_type, $id_user);
        break;

    case 2:
        $turnover = new CAllowance();
        $turnover->create($id_turnover_type, $id_user);
        break;

    case 3:
        $turnover = new CSale();
        $turnover->create($id_turnover_type, $id_user);
        break;
}

if (!$turnover->add_item($id_product, $quantity)) {
    echo 0;
    return;
}
//если обращение к скрипту не через ajax
if (!isset($_POST['ajax']))
    header("Location: " . $_SERVER['HTTP_REFERER'] . "#" . $id_product);
else
    echo 1;
?>

