<?php

function p_title() {
    return "Статьи. Спортивный-экипировочный центр Sport Class";
}

function p_description() {
    return "Статьи. Спортивный-экипировочный центр Sport Class";
}

function p_breadcrumbs() {
    return "Статьи";
}

function p_content() {
    $from = 0;
    if (isset($_GET['from']) && is_numeric($_GET['from'])) {
        $from = (int) $_GET['from'];
    }
    $part = 10;

    $headings = "";
    if(isset($_GET['id_heading']) && is_numeric($_GET['id_heading']))
        $headings = " AND heading.id_heading='".$_GET['id_heading']."' ";
    
    $query = "SELECT * FROM article, heading WHERE heading.id_heading=article.id_heading $headings AND heading.id_heading<>'13' ORDER BY article.id_article DESC";

    $result = mysql_query($query . " LIMIT $from,$part ");

    while ($row = mysql_fetch_array($result)) {
        $article = new CArticle_fe($row['id_article']);
        $article->showPreview();
    }

    $result = mysql_query($query);
    if (!$result)
        return;
    $all = mysql_num_rows($result);

    CPaginator::show($all, $from, $part);
}