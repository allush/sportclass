<?php

function p_title() {
    return "Поиск. Спортивный-экипировочный центр Sport Class";
}

function p_description() {
    return "Поиск. Спортивный-экипировочный центр Sport Class";
}

function p_breadcrumbs() {
    return "Поиск";
}

function p_content() {
    if (!isset($_POST['keyword']) || empty($_POST['keyword']))
        return 0;
    $keyword = trim($_POST['keyword']);

    $result = mysql_query("SELECT * FROM `product` WHERE `name` LIKE '%$keyword%'");

    if (!mysql_num_rows($result))
        return "К сожалению, по запросу ''<i>$keyword</i>'' ничего не найдено";

//Выводим товары
    $i = 0;
    while ($row = mysql_fetch_array($result)) {
        $product = new CProduct_fe($row['id_product']);
        $product->show(++$i);
    }
}

?>
