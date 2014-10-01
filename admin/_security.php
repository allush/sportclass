<?php

function p_permission() {
    return array(array("1" => array("write" => "0", "read" => "1")));
}
function p_title() {
    return "Безопасность";
}

function p_description() {
    return "Безопасность";
}

function p_dark_block() {
    ?>
    <a href="index.php?p=user">Пользователи</a>
    <a href="index.php?p=role">Роли</a>
    <a href="index.php?p=permission">Объекты доступа</a>
    <a href="index.php?p=log">Логи</a>
    <?php
}

function p_content() {
    
}
?>