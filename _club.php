<?php

function p_title() {
    return "Спортивный клуб. Спортивный-экипировочный центр Sport Class";
}

function p_description() {
    return "Спортивный клуб. Спортивный-экипировочный центр Sport Class";
}

function p_breadcrumbs() {
    return "Спортивный клуб";
}
function p_content() {         
    $article = new CArticle_fe(29);
    $article->showDetail();
   
}