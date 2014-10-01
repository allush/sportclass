<?php

session_start();
include '../common/CUser.php';
include '../common/connect.php';
include 'CProduct_be.php';
include 'wlog.php';

$user = new CUser($_SESSION['id_user']);
if (!$user->has_permission(array(array("3" => array("write" => "1", "read" => "1"))))) {
    header("Location: index.php?p=permission_denied");
    return;
}

if (!isset($_POST['id_product']) || !is_array($_POST['id_product']))
    return;

$ids_product = $_POST['id_product'];

//установка каталогов
foreach ($ids_product as $id_product) {
    $product = new CProduct_be($id_product);

    if (isset($_POST['id_catalog']) && is_array($_POST['id_catalog'])) {
        if (!mysql_query("DELETE FROM `product_catalog` WHERE `id_product`='$id_product' "))
            continue;
        foreach ($_POST['id_catalog'] as $id_catalog)
            $product->set_product_catalog($id_catalog);
    }

    if (isset($_POST['discount']))
        $product->set_discount($_POST['discount']);

    if (isset($_POST['visible']))
        $product->set_visible($_POST['visible']);
}

header("Location: " . $_SERVER['HTTP_REFERER'] . "#" . $id_product);
?>

