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

$parent = $_POST['parent'];
$name = $_POST['name'];
$description = $_POST['description'];
$cover = $_FILES['cover'];

$catalog = new CCatalog();
$catalog->create($name, $description, $cover, $parent);


include_once '../common/CHeading.php';

$headings = array();
if (is_array($_POST['id_heading'])) {
    foreach ($_POST['id_heading'] as $heading)
        $headings[] = new CHeading($heading);
}

$catalog->setHeadings($headings);

header("Location: index.php?p=catalog");
?>
