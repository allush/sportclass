<?php

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
        Здесь будут новости
       <?php
         $sql = "SELECT * FROM news";
         $result = mysql_query($sql)  or die(mysql_error());

         while ($row = mysql_fetch_assoc($result))
         {
             $id = $row['id'];
            $text = $row['text'];
             echo "<div>$id</div>";
             echo "<div>$text</div>";
         }

       ?>
    </p>


<?php
}