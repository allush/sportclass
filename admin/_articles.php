<?php

/**
 * 
 */
function p_permission() {
    return array(array("13" => array("write" => "0", "read" => "1")));
}

function p_title() {
    return "Статьи";
}

function p_description() {
    return "Статьи";
}

function p_dark_block() {
    ?>
    <a href='index.php?p=articles'>Все статьи</a>
    <a href='index.php?p=article_add'>Добавить</a>
    <?php
}

function p_content() {
    include 'CArticle_be.php';

    $resultHeading = mysql_query("SELECT * FROM `heading` ORDER BY `name` ASC");
    while ($rowHeading = mysql_fetch_array($resultHeading)) {
        echo "<div><b>".$rowHeading['name']."</b></div>";
        $result = mysql_query("SELECT `id_article` FROM `article` WHERE `id_heading`='".$rowHeading['id_heading']."' ORDER BY `date` ASC");
        while ($row = mysql_fetch_array($result)) {
            $article = new CArticle_be($row[0]);
            $article->show();
        }
    }
}
?>
