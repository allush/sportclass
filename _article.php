<?php

function p_title() {
    if (!isset($_GET['id']) && is_numeric($_GET['id']))
        return;
    $id = (int)$_GET['id'];
    
    $article = new CArticle_fe($id);
    return $article->title().". Спортивный-экипировочный центр Sport Class";
}

function p_description() {
    return "";
}

function p_breadcrumbs() {
    return "";
}

function p_content() {
     if (!isset($_GET['id']) && is_numeric($_GET['id']))
        return;
    $id = (int)$_GET['id'];
    
    $article = new CArticle_fe($id);
    $article->showDetail();
   
}