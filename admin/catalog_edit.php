<?php

if ($_SERVER['REQUEST_METHOD'] != "POST")
    return;

session_start();
include '../common/connect.php';
include '../common/CUser.php';
include '../common/CCatalog.php';
include 'thumbnail.php';
include 'wlog.php';

$user = new CUser($_SESSION['id_user']);
if (!$user->has_permission(array(array("2" => array("write" => "1", "read" => "1"))))) {
    header("Location: index.php?p=permission_denied");
    return;
}

$id_catalog = $_POST['id_catalog'];
$name = $_POST['name'];
$description = $_POST['description'];
$parent = $_POST['parent'];

$catalog = new CCatalog($id_catalog);
$catalog->set_name($name);
$catalog->set_description($description);
$catalog->set_cover($_FILES['cover']);
$catalog->set_parent($parent);

include_once '../common/CHeading.php';

$headings = array();
if (is_array($_POST['id_heading'])) {
    foreach ($_POST['id_heading'] as $heading)
        $headings[] = new CHeading($heading);
}

$catalog->setHeadings($headings);

header("Location: index.php?p=catalog");
?>
