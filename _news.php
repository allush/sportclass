﻿<?php
include 'common/CImageHandler.php';


function p_title()
{
    return "Новости";
}

function p_description()
{
    return "Новости";
}

function p_breadcrumbs()
{
    return "Новости";
}

function p_content()
{

    ?>
    <div class="heading">Новости</div>
    <?php

    $from = 0;
    if (isset($_GET['from']) && is_numeric($_GET['from'])) {
        $from = (int)$_GET['from'];
    }
    $part = 10;
    $query = "SELECT `date`, `text`, `id` FROM `news` ORDER BY `id` DESC";
    $result = mysql_query($query . " LIMIT $from,$part ") or die(mysql_error());
    if (!$result)
        return;


    // Вывод результатов
    while ($row = mysql_fetch_assoc($result)) {
        echo '<div class="news">';
        echo '<div>' . date('d.m.Y H:i', $row['date']) . '</div>';
        echo '<div>' . $row['text'] . '</div>';
        $photoresult = mysql_query('SELECT * FROM `photo_news` WHERE `news_id`=' . $row['id']);
        while ($photorow = mysql_fetch_assoc($photoresult)) {
            $path = (string)$photorow['path'];
            echo '<div class="news-photo">';
            echo '<div class="news-photo-name">' . $photorow["text"] . '</div>';
            echo '<img class="picture" src="' . $path . '" alt=""  />';
            echo '</div>';
        }
        echo '<div class="clearer"></div>';
        echo '</div>';
    }

    $result = mysql_query($query);
    if (!$result)
        return;
    $all = mysql_num_rows($result);

    CPaginator::show($all, $from, $part);
    ?>

<?php
}
