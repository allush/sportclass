<?php
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
    <p>
        <?php

        // Количество новостей на странице
        $on_page = 8;
        // Получаем количество записей таблицы news
        $query = "SELECT COUNT(*) FROM `news`";
        $res = mysql_query($query);
        $count_records = mysql_fetch_row($res);
        $count_records = $count_records[0];


        // Получаем количество страниц
        // Делим количество записей на количество новостей на странице
        // и округляем в большую сторону
        $num_pages = ceil($count_records / $on_page);


        // Текущая страница из GET-параметра page
        // Если параметр не определен, то текущая страница равна 1
        $current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

        // Если текущая страница меньше единицы, то страница равна 1
        if ($current_page < 1) {
            $current_page = 1;
        }
// Если текущая страница больше общего количества страница, то
// текущая страница равна количеству страниц
        elseif ($current_page > $num_pages) {
            $current_page = $num_pages;
        }


        // Начать получение данных от числа (текущая страница - 1) * количество записей на странице
        $start_from = ($current_page - 1) * $on_page;


        // Формат оператора LIMIT <ЗАПИСЬ ОТ>, <КОЛИЧЕСТВО ЗАПИСЕЙ>
        $query = "SELECT `id`, `text` FROM `news` ORDER BY `id` DESC LIMIT $start_from, $on_page";
        $res = mysql_query($query);


        // Вывод результатов
        while ($row = mysql_fetch_assoc($res)) {

            echo '<div>' . $row['id'] . '</div>';
            echo '<div>' . $row['text'] . '</div>';
            $photoresult = mysql_query('SELECT * FROM `photo_news` WHERE `news_id`=' . $row['id']);
            while ($photorow = mysql_fetch_assoc($photoresult)) {
                $path = (string)$photorow['path'];
                echo "<img src='$path' alt='' width='50%' height='50%'/>";

            }

        }

        // Вывод списка страниц
        echo '<p>';
        for ($page = 1; $page <= $num_pages; $page++) {
            if ($page == $current_page) {
                echo '<strong>' . $page . '</strong> &nbsp;';
            } else {
                echo '<a href="index.php?p=news&page=' . $page . '">' . $page . '</a> &nbsp;';
            }
        }
        echo '</p>';

        ?>

    </p>


<?php
}
