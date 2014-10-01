<?php

function p_title() {
    if (!isset($_GET['code']))
        return;
    $code = $_GET['code'];

    $video = new CVideo_fe($code);
    return $video->title() . " Видео. Спортивный-экипировочный центр Sport Class";
}

function p_description() {
    if (!isset($_GET['code']))
        return;
    $code = $_GET['code'];

    $video = new CVideo_fe($code);
    return $video->title() . " Видео. Спортивный-экипировочный центр Sport Class";
}

function p_breadcrumbs() {
    if (!isset($_GET['code']))
        return;
    $code = $_GET['code'];

    $video = new CVideo_fe($code);
    return "<a href='index.php?p=mks'>Видеоуроки</a> | " . $video->title();
}

function p_content() {

    if (!isset($_GET['code']))
        return;
    $code = $_GET['code'];

    $video = new CVideo_fe($code);
   ?>

<div class="heading">        <?php echo $video->title(); ?></div>
<iframe style="margin: 8px 0;" width="420" height="315" src="http://www.youtube.com/embed/<?php echo $video->code(); ?>" frameborder="0" allowfullscreen></iframe>

<p class="heading">Все комментарии (<?php echo sizeof($video->comments()); ?>)</p>
    <?php
    foreach ($video->comments() as $comment)
        $comment->show();
    ?>

    <div class="heading">Новые видео</div>
    <?php
    $result = mysql_query("SELECT * FROM `video` WHERE `code` <> '$code' ORDER BY `id_video` DESC LIMIT 0,4");
    if ($result) {
        while ($row = mysql_fetch_array($result)) {
            $v = new CVideo_fe($row['code']);
            $v->show();
        }
    }
}

