<?php

function p_title() {
    return "Видео лыжные гонки, инвентарь Спортивный-экипировочный центр Sport Class";
}

function p_description() {
    return "Видео лыжные гонки, инвентарь Спортивный-экипировочный центр Sport Class";
}

function p_breadcrumbs() {
    return "Видео";
}

function p_content() {
    $from = 0;
    if (isset($_GET['from']) && is_numeric($_GET['from'])) {
        $from = (int) $_GET['from'];
    }
    $part = 10;

    $query = "SELECT * FROM `video` ORDER BY `id_video` DESC";

    $result = mysql_query($query . " LIMIT $from,$part ");
    if (!$result)
        return 0;

    while ($row = mysql_fetch_array($result)) {
        $video = new CVideo_fe($row['code']);
        $video->show();
    }
   ?>

    <noscript>
    <?php
    $result = mysql_query($query);
    if (!$result)
        return;
    $all = mysql_num_rows($result);

    CPaginator::show($all, $from, $part);
    ?>
    </noscript>


    <script type="text/javascript">
        var num_default_loaded_video = 10;
        $(window).scroll(function(){
            tbutton();
            load_video();
        });
    </script>
    <?php
}