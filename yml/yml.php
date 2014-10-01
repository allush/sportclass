<?php
error_reporting(E_ALL);
include '../common/connect.php';
include '../CProduct_fe.php';

function categories($parent, $depth, &$output) {
    $result = mysql_query("SELECT * FROM `catalog` WHERE `parent`='$parent'");
    if (!$result)
        return;

    while ($row = mysql_fetch_array($result)) {
        $name = $row['name'];
        $id_catalog = $row['id_catalog'];

        $output .= '<category id="' . $id_catalog . '"';
        if ($depth > 0)
            $output .= ' parentId="' . $parent . '"';
        $output .= '>' . $name . '</category>';

        categories($id_catalog, $depth + 1, $output);
    }
}

function yml() {
    $output = '<?xml version="1.0" encoding="utf-8"?>';

    $output .= '<!DOCTYPE yml_catalog SYSTEM "shops.dtd">';

    $output .= '<yml_catalog date="' . date("Y-m-d H:i", mktime()) . '">';

    $output .= '<shop>';
    $output .= '<name>Магазин Штуки</name>';
    $output .= '<company>ИП Лушникова Е.И.</company>';
    $output .= '<url>http://www.shtuki.pro/</url>';

    $output .= '<currencies>';
    $output .= '<currency id="RUR" rate="1"/>';
    $output .= '</currencies>';

    $output .= '<categories>';

    categories(0, 0, $output);

    $output .= '</categories>';

    $output .= '<offers>';

    $result = mysql_query("SELECT * FROM `product` WHERE `existence`>'0' AND `visible`='1' ORDER BY `id_product` DESC");
    if (!$result)
        return;

    while ($row = mysql_fetch_array($result)) {
        $product = new CProduct_fe($row['id_product']);

        $output .= '<offer id="' . $product->id_product() . '" available="true">';
        $output .= '<url>http://www.shtuki.pro/index.php?p=product&amp;id=' . $product->id_product() . '</url>';
        $output .= '<price>' . $product->cost() . '.00</price>';
        $output .= '<currencyId>RUR</currencyId>';

        $catalogs = $product->catalogs();
        $output .= '<categoryId>' . $catalogs[0]->id_catalog() . '</categoryId>';
        $output .= '<picture>http://www.shtuki.pro/' . $product->picture() . '</picture>';
        $output .= '<store>true</store>';
        $output .= '<pickup>true</pickup>';
        $output .= '<delivery>false</delivery>';
        $output .= '<name>' . htmlspecialchars($product->name(), ENT_QUOTES) . '</name>';
        $output .= '<description>' . htmlspecialchars($product->description(), ENT_QUOTES) . '</description>';
        $output .= '<country_of_origin>Россия</country_of_origin>';
        $output .= '</offer>';
    }
    $output .= '</offers>';
    $output .= '</shop>';
    $output .= '</yml_catalog>';


    $file = fopen("yml.xml", "w");
    if ($file) {
        fwrite($file, $output);
        fclose($file);
    }
}

yml();
?>
