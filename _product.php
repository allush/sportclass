
<?php

function p_title() {
    if (!isset($_GET['id']) && is_numeric($_GET['id']))
        return;

    $id_product = (int)$_GET['id'];

    $product = new CProduct_fe($id_product);
    return $product->name(). ". Спортивный-экипировочный центр Sport Class ";
}

function p_description() {
    if (!isset($_GET['id']) && is_numeric($_GET['id']))
        return;
    $id_product = (int)$_GET['id'];

    $product = new CProduct_fe($id_product);
    return $product->name(). ". Спортивный-экипировочный центр Sport Class ";
}

function p_breadcrumbs() {
    if (!isset($_GET['id']) && is_numeric($_GET['id']))
        return;
    $id_product = $_GET['id'];
    settype($id_product, "int");

    $product = new CProduct_fe($id_product);

    $catalogs = $product->catalogs();
    $parents_name = $catalogs[0]->parents_name();

    array_push($parents_name, "<a href='index.php?p=products&catalog=" . $catalogs[0]->id_catalog() . "'>" . $catalogs[0]->name() . "</a>");

    $parents_name = implode(" | ", $parents_name);
    echo "<a href='index.php?p=products'>Каталог</a> | " . $parents_name . " | " . $product->name();
}

function p_content() {
    if (!isset($_GET['id']) && is_numeric($_GET['id']))
        return;
    $id_product = (int) $_GET['id'];

    $product = new CProduct_fe($id_product);
    $product->card();
}

?>
