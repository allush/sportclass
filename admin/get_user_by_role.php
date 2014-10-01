<?php
error_reporting(E_ALL);
session_start();
include '../common/connect.php';
include '../common/CUser.php';

include 'wlog.php';
$user = new CUser($_SESSION['id_user']);
if (!$user->has_permission(array(array("11" => array("write" => "0", "read" => "1"))))) {
    header("Location: index.php?p=permission_denied");
    return;
}

if (!isset($_POST['roles']) || !is_array($_POST['roles']))
    return;

foreach ($_POST['roles'] as $id_role) {
    $result = mysql_query("SELECT * FROM `user` WHERE `deleted`='0' AND `id_user` IN (SELECT `id_user` FROM `user_role` WHERE `id_role` = '$id_role' GROUP BY `id_user`) ORDER BY `last_name` ASC");
    if (!$result)
        return;
    $role = new CRole($id_role);
    echo "<optgroup label='".$role->name()."'>";
    while ($row = mysql_fetch_array($result)) {
        $user = new CUser($row['id_user']);
        echo "<option selected='selected' value='" . $user->id_user() . "'>" . $user->last_name() . " " . $user->first_name() . "</option>";
    }
}
?>
