<?php

function p_permission() {
    return array(array("14" => array("write" => "0", "read" => "1")));
}

function p_title() {
    return "Рубрики";
}

function p_description() {
    return "Рубрики";
}

function p_dark_block() {
    ?>
    <a href='index.php?p=heading'>Все рубрики</a>
    <a href='index.php?p=heading_add'>Добавить</a>
    <?php
}

function p_content() {
    include '../common/CHeading.php';
    echo "<div><b>Показаны на главной</b></div>";
    $result = mysql_query("SELECT `id_heading` FROM `heading` WHERE `onMain`='1' ORDER BY `name` ASC");
    while($row = mysql_fetch_array($result)){
        $heading = new CHeading($row['id_heading']);
        $heading->show();
    }
    echo "<br>";
    echo "<div><b>В общей ленте статей</b></div>";
    $result = mysql_query("SELECT `id_heading` FROM `heading`  WHERE `onMain`='0' ORDER BY `name` ASC");
    while($row = mysql_fetch_array($result)){
        $heading = new CHeading($row['id_heading']);
        $heading->show();
    }
}
?>
