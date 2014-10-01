<?php

function p_permission() {
    return array(array("14" => array("write" => "0", "read" => "1")));
}

function p_title() {
    return "Добавление рубрики";
}

function p_description() {
    return "Добавление рубрики";
}

function p_dark_block() {
    ?>
    <a href='index.php?p=heading'>Все рубрики</a>
    <a href='index.php?p=heading_add'>Добавить</a>
    <?php
}

function p_content() {
    ?>
    <form method="post" action="heading_add.php">
        <p>Название</p>
        <input type="text" name="name" />
        
        <p>Показывать на главной странице</p>
        <input type="checkbox" name="onMain" />
        
        <p></p>
        <button>Сохранить</button>
    </form>




    <?php
}
?>
