<?php

function p_title() {
    return "Доставка и оплата. Спортивный-экипировочный центр Sport Class";
}

function p_description() {
    return "Доставка и оплата. Спортивный-экипировочный центр Sport Class";
}

function p_breadcrumbs() {
    return "Доставка и оплата";
}
function p_content() {         
    $article = new CArticle_fe(28);
    $article->showDetail();
   
}