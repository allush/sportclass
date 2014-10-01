<?php

function p_permission() {
    return array(array("7" => array("write" => "0", "read" => "1")));
}

function p_title() {
    return "Настройки";
}

function p_description() {
    return "Настройки";
}

function p_dark_block() {
    ?>
    <?php
}

function p_content() {
    $result = mysql_query("SELECT * FROM `settings`") or die(mysql_error());
    ?>
    <form action="settings.php" method="post" />
        <table>
           
        </table>
    </form>
    <?php
}
?>