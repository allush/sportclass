<?php

function p_permission() {
    return array(array("8" => array("write" => "0", "read" => "1")));
}

function p_title() {
    return "Видео";
}

function p_description() {
    return "Видео";
}

function p_dark_block() {
    ?>
    <a href='index.php?p=video'>Все видео</a>
    <?php
    $user = new CUser($_SESSION['id_user']);
    if ($user->has_permission(array(array("8" => array("write" => "1", "read" => "1"))), 0)) {
        ?>
        <a href='index.php?p=video_add'>Добавить</a>
        <?php
    }
}

function p_content() {
    include_once 'CVideo_be.php';
    
    $resultHeading = mysql_query("SELECT * FROM `heading` ORDER BY `name` ASC");
    while ($rowHeading = mysql_fetch_array($resultHeading)) {
        echo "<div><b>" . $rowHeading['name'] . "</b></div>";
        
        $result = mysql_query("SELECT * FROM `video` WHERE `id_heading`='".$rowHeading['id_heading']."' ORDER BY `id_video` DESC");
        while ($row = mysql_fetch_array($result)) {
            $video = new CVideo_be($row['code']);
            $video->show();
        }
        if(!$row)
            echo "<p style='padding-left: 12px;'>Нет видео</p>";
    }
}
?>