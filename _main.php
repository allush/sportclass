
<?php

function p_title() {
    return "Главная. Спортивный-экипировочный центр Sport Class";
}

function p_description() {
    return "Главная. Спортивный-экипировочный центр Sport Class";
}

function p_breadcrumbs() {
    return "Главная";
}

function p_content() {
    ?>


    <?php
    $result = mysql_query("SELECT * FROM `product` WHERE `visible`='1' AND `id_product` IN (SELECT `id_product` FROM `product_catalog` GROUP BY `id_product`) ORDER BY `date_last_edit` DESC LIMIT 0,6");
    if (!$result)
        return;
    echo "<div class='heading'>НОВИНКИ</div>";
    $i = 1;
    while ($row = mysql_fetch_array($result)) {
        $product = new CProduct_fe($row['id_product']);
        $product->show($i++);
    }
    ?>

    <div class='clearer' style='height: 24px;'></div>

    <div style="width: 45%; float: left; ">
        <?php
        $result = mysql_query("SELECT * FROM `video` ORDER BY `id_video` DESC LIMIT 0,3");
        if (!$result)
            return;
        echo "<div class='heading'><a href='index.php?p=videos'>ВИДЕО</a></div>";
        while ($row = mysql_fetch_array($result)) {
            $video = new CVideo_fe($row['code']);
            $video->show();
        }
        ?>
        <div><a class='backLink' href='index.php?p=videos'>Все видео &rArr;</a></div>
    </div>

    <div style="width: 45%; float: right;">
        <?php
        $result = mysql_query("SELECT * FROM article, heading WHERE heading.id_heading=article.id_heading AND heading.id_heading<> '13' ORDER BY article.id_article DESC LIMIT 0,10");
        if (!$result)
            return;
        echo "<div class='heading'><a href='index.php?p=articles'>СТАТЬИ</a></div>";
        while ($row = mysql_fetch_array($result)) {
            $article = new CArticle_fe($row['id_article']);
            $article->show();
        }
        ?>
        <div><a class='backLink' href='index.php?p=articles'>Все статьи &rArr;</a></div>
    </div>

    <?php
    echo "<div class='clearer'></div>";
}
?>