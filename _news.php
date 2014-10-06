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
        $sql = "SELECT * FROM news ORDER BY `id` DESC ";
        $result = mysql_query($sql) or die(mysql_error());

        while ($row = mysql_fetch_assoc($result)) {
            $id = $row['id'];
            $text = $row['text'];
            echo "<div>$id</div>";
            echo "<div>$text</div>";
            $photoresult = mysql_query('SELECT * FROM `photo_news` WHERE `news_id`=' . $id);
            while ($photorow = mysql_fetch_assoc($photoresult))
            {
                $img=new CImageHandler();
                $path= (string)$photorow['path'];
//                $img->load($path);
//                $img->show(); не работает, пишет Cannot modify header information - headers already sent by
               echo "<img src='$path' alt='' width='50%' height='50%'/>";

            }
        }

        ?>
    </p>


<?php
}