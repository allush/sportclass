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

if (!isset($_POST['name']))
    return;
$name = $_POST['name'];

if (!isset($_POST['id_role']))
    return;

if (!is_numeric($_POST['id_role']))
    return;

$id_role = $_POST['id_role'];

$role = new CRole($id_role);
$role->set_name($name);

mysql_query("DELETE FROM `role_permission` WHERE `id_role`='$id_role'") or die(mysql_error());

$result = mysql_query("SELECT * FROM `permission`") or die(mysql_error());
while ($row = mysql_fetch_array($result)) {
    $read = 0;
    if (is_array($_POST['read']) && in_array($row['id_permission'], $_POST['read']))
        $read = 1;

    $write = 0;
    if (is_array($_POST['write']) && in_array($row['id_permission'], $_POST['write']))
        $write = 1;

    if ($read == 0 && $write == 0)
        continue;
    $role->add_permission($row['id_permission'], $read, $write);
}

$referer = $_SERVER['HTTP_REFERER'];
if (isset($_POST['referer']))
    $referer = $_POST['referer'];

header("Location: " . $referer);
?>
