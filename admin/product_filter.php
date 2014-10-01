<?php

if ($_SERVER['REQUEST_METHOD'] != "POST")
    return;

session_start();
include '../common/connect.php';
include '../common/CUser.php';
include 'CProduct_be.php';
include 'wlog.php';

$user = new CUser($_SESSION['id_user']);
if (!$user->has_permission(array(array("3" => array("write" => "0", "read" => "1"))))) {

    if (isset($_POST['ajax'])) {
        echo -1;
        return;
    }
    header("Location: index.php?p=permission_denied");
    return;
}

$conditions = array();

if (isset($_POST['keyword'])) {
    if (!isset($_POST['ajax']))
        $conditions[] = "(`name` LIKE '%" . $_POST['keyword'] . "%')";
    elseif (!empty($_POST['keyword']))
        $conditions[] = "(`name` LIKE '%" . $_POST['keyword'] . "%')";
}

// показать скрытые товары
if (isset($_POST['hidden'])) {
    if (!isset($_POST['ajax']))
        $conditions[] = "(`visible` IS NULL OR `visible`='0')";
    elseif ($_POST['hidden'] == "true")
        $conditions[] = "(`visible` IS NULL OR `visible`='0')";
}

// показать товары вне каталога
if (isset($_POST['without_catalog'])) {
    if (!isset($_POST['ajax']))
        $conditions[] = "(`id_product` NOT IN (SELECT `id_product` FROM `product_catalog` GROUP BY `id_product`))";
    elseif ($_POST['without_catalog'] == "true")
        $conditions[] = "(`id_product` NOT IN (SELECT `id_product` FROM `product_catalog` GROUP BY `id_product`))";
}

// показать товары которых нет в наличии
if (isset($_POST['existence'])) {
    if (!isset($_POST['ajax']))
        $conditions[] = "(`existence`='0')";
    elseif ($_POST['existence'] == "true")
        $conditions[] = "(`existence`='0')";
}

// показать удаленные товары
if (isset($_POST['deleted'])) {
    if ($_POST['deleted'] == "true")
        $conditions[] = "(`deleted`='1')";
    else
        $conditions[] = "(`deleted`='0')"; //искать всегда только те товары, которые НЕ удалены
}

// показать товары  со скидкой
if (isset($_POST['discount'])) {
    if (!isset($_POST['ajax']))
        $conditions[] = "(`discount`>'0')";
    elseif ($_POST['discount'] == "true")
        $conditions[] = "(`discount`>'0')";
}

// показать из каталога
if (isset($_POST['id_catalog'])) {
    $id_catalog = (int) $_POST['id_catalog'];
    $children = "";
    if ($id_catalog) {
        $catalog = new CCatalog($_POST['id_catalog']);
        $children = $catalog->children();
        $children[] = $_POST['id_catalog'];
        $children = implode(",", $children);
        $children = " WHERE `id_catalog` IN ($children)";
        $conditions[] = "(`id_product` IN (SELECT `id_product` FROM `product_catalog` $children ))";
    }
}


if (!sizeof($conditions))
    die("0");

$conditions = "WHERE " . implode(" AND ", $conditions);

$i = 0;
$result = mysql_query("SELECT * FROM `product` $conditions") or die(mysql_error());
while ($row = mysql_fetch_array($result)) {
    $i++;
    $product = new CProduct_be($row['id_product']);
    $product->show();
}
if (!$i) {
    if (isset($_POST['ajax'])) {
        echo 0;
        return;
    }
}
?>
