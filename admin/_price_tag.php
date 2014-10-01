<?php

function p_permission() {
    return array(array("3" => array("write" => "0", "read" => "1")));
}

function p_title() {
    return "Список ценников";
}

function p_description() {
    return "Список ценников";
}

function p_dark_block() {
    ?>
    <a href='index.php?p=product'>Все товары</a>
    <a href="index.php?p=product_filter">Поиск</a>
    <?php
    $user = new CUser($_SESSION['id_user']);
    if ($user->has_permission(array(array("3" => array("write" => "1", "read" => "1"))), 0)) {
        ?>
        <a href='index.php?p=product_add'>Добавить</a>
        <?php
    }
    ?>
    <a href="index.php?p=price_tag">Ценники</a>
    <?php
}

function p_content() {
    ?>
    <form name="generate_price_tag" action="generate_price_tag.php" method="post">
        <fieldset>
            <legend>Генерировать ценники</legend>
            <p><input id="all" type="radio" name="type" value="all" /> <label for="all">Для всех товаров (может занять много времени)</label></p>
            <p><input id="selected" type="radio" name="type" value="selected" checked="yes" /> <label for="selected">Для товаров из списка ниже</label></p>
            <p>
                <button id="generate_price_tag" style="margin-top: 8px;">Сгенерировать ценники</button>
                <button id="clear_price_tag"    style="margin-top: 8px;">Очистить список ценников</button>
            </p>
        </fieldset>
    </form>

    <div id="result_generate_price_tag"></div>
    <?php
    $i = 0;
    $result = mysql_query("SELECT * FROM `price_tag` ORDER BY `id_price_tag` ASC ");
    while ($row = mysql_fetch_array($result)) {
        $product = new CProduct_be($row['id_product']);
        ?>
        <div class="list_item price_tag_item " id="price_tag<?php echo $product->id_product(); ?>">
            <?php echo $product->name(); ?>
            <a class='small_grey right <?php echo $product->id_product(); ?>'>Удалить</a>
            <div class="clearer"></div>
        </div>
        <?php
    }
    ?>

    <script type="text/javascript">
        // клик по кнопке "Удалить"
        $(".price_tag_item a").click(function(){
            if(!confirm('Вы уверены?')) 
                return false;
            var id_product = $(this).attr("class").split(" ")[2];
            price_tag_delete(id_product);
        })
                
        // клик по кнопке "Сгенерировать"
        $("#generate_price_tag").click( function() {
            generate_price_tag(); 
            return false; //чтобы форма не отправлялась
        }) 
                
        // клик по кнопке "Очистить список ценников"
        $("#clear_price_tag").click( function(){                 
            if(!$(".price_tag_item a").size()){                    
                $("#result_generate_price_tag").html("Список ценников пуст");
                return false;
            }
            if(!confirm('Вы уверены?')) 
                return false;
            clear_price_tag();  
            return false; //чтобы форма не отправлялась
        })
    </script>
    <?php
}
?>