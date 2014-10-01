<?php

function p_permission() {
    return array(array("3" => array("write" => "0", "read" => "1")));
}

function p_title() {
    return "Товары";
}

function p_description() {
    return "Товары";
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

function p_breadcrumbs() {
    $breadcrumbs = "<a class='grey' href='index.php?p=catalog'>Каталог</a>";
    if (isset($_GET['catalog'])) {
        $catalog = new CCatalog($_GET['catalog']);
        $parents_name = $catalog->parents_name();

        array_push($parents_name, $catalog->name());

        $parents_name = implode("<span class='grey'> &middot </span>", $parents_name);

        $breadcrumbs .= "<span class='grey'> &middot </span>" . $parents_name;
    }
    return $breadcrumbs;
}

function view_pages($all, $part, $from) {
    $mod = $all % $part;
    $num_pages = (int) ( $all / $part);
    if ($mod)
        ++$num_pages;
    ?>

    <div class="pages">
        <form name="page" action="index.php" method="get" >
            <input type="hidden" name="p" value="<?php echo $_GET['p']; ?>" />
            <?php if (isset($_GET['catalog']) && is_numeric($_GET['catalog'])) { ?>
                <input type="hidden" name="catalog" value="<?php echo $_GET['catalog']; ?>" />
                <?php
            }
            $previous = $from - $part;
            $catalog = "";
            if (isset($_GET['catalog']) && is_numeric($_GET['catalog']))
                $catalog = "&catalog=" . $_GET['catalog'];

            if ($previous >= 0) {
                ?>
                <a href="<?php echo $_SERVER['SCRIPT_NAME'] . "?p=" . $_GET['p'] . $catalog . "&from=$previous"; ?>">Назад</a>
                <?php
            }
            ?> Страница:
            <select name="from" onchange="submit_form()">
                <?php for ($i = 1; $i <= $num_pages; $i++) { ?>
                    <option <?php if (($from / $part) == ($i - 1)) echo "selected='yes'" ?> value='<?php echo (($i - 1) * $part); ?>'><?php echo $i; ?></option>
                    <?php
                }
                ?>
            </select>
            <noscript><input type="submit" value="OK"/></noscript>
            <?php
            $next = $from + $part;
            if ($next < $all) {
                ?>
                <a href="<?php echo $_SERVER['SCRIPT_NAME'] . "?p=" . $_GET['p'] . $catalog . "&from=$next"; ?>">Вперед</a>
                <?php
            }
            ?>
            Всего страниц: <?php echo $num_pages; ?>
        </form>

    </div>

    <?php
}

function p_content() {
    $id_catalog = 0;
    $children = "";
    if (isset($_GET['catalog'])) {
        $id_catalog = (int) $_GET['catalog']; // показать все товары
        $catalog = new CCatalog($id_catalog);

        $children = array();
// Получить всех потомков данного каталога, чтобы выбрать все товары из них
        $children = $catalog->children();
//добавить сам каталог в список
        array_push($children, $id_catalog);

        $children = implode(",", $children);
        $children = " product_catalog.id_catalog IN (".$children.") AND ";
    }


// Выводим товары этого каталога и всех подкаталогов (ВИДИМЫЕ и НЕУДАЛЕНЫЕ)
    $query = "SELECT product_catalog.id_product FROM product_catalog, product WHERE $children product.id_product=product_catalog.id_product AND product.visible='1' AND product.deleted='0' GROUP BY id_product ORDER BY id_product DESC";

// количество выводимых позиций на странице

    $part = 20;
    if (isset($_COOKIE['part_admin']) && is_numeric($_COOKIE['part_admin'])) {
        $part = $_COOKIE['part_admin'];
        settype($part, "int");
    }
    $from = 0;
    if (isset($_GET['from']) && is_numeric($_GET['from'])) {
        $from = $_GET['from'];
        settype($from, "int");
    }

    //подсчет обзего кол-ва товаров выборки
    $result = mysql_query($query) or die(mysql_error());
    if (!$result)
        return;
    $all = mysql_num_rows($result);

    $result = mysql_query($query . " LIMIT $from,$part ") or die(mysql_error());
    if (!$result)
        return;
    ?>

    <form name="part" action="../common/set_part.php" method="post" style="margin: 4px 4px 0 0 ;text-align: right; float: right; width: 300px;" >
        Показывать на странице по
        <select name="part" onchange="set_part()">
            <?php
            for ($i = 20; $i <= 100; $i+=20) {
                ?>
                <option <?php if ($part == $i) echo "selected='yes'" ?> value='<?php echo $i; ?>'><?php echo $i; ?></option>
                <?php
            }
            ?>
        </select>
        товаров
        <noscript><input type="submit" value="OK"/></noscript>
    </form>
    <?php
    view_pages($all, $part, $from);   #вывести список страниц
    ?>
    <div id="popup"></div>
    <div class="manageBlock">
        <button id='show_hide'>Развернуть все</button>
        <button id='select_deselect'>Выделить все</button>

        <p class="groupAction">
            С выделенными:
            <a onclick="setProductOptions();"> <img width="16" title="Назначить опции" alt="" src="img/catalog.png">  </a>
            <a onclick="priceTagSelectedProduct();"> <img width="16" title="Создать ценники" alt="" src="img/price_tag.png">  </a>
            <a onclick="joinProducts();"> <img width="16" title="Объединить" alt="" src="img/join.png">  </a>
            <a onclick="saveSelectedProduct();"> <img width="16" title="Сохранить" alt="" src="img/save.png"> </a>
            <a onclick="undeleteSelectedProduct();"> <img width="16" title="Восстановить" alt="" src="img/undelete.png"> </a>
            <a onclick="if(!confirm('Вы действительно хотите удалить выделенные товары?')) return false; deleteSelectedProduct();"> <img width="16" title="Удалить" alt="" src="img/delete.png"> </a>
        </p>
    </div>
    <?php
    $i = 0;
    while ($row = mysql_fetch_array($result)) {
        $i++;
        $product = new CProduct_be($row['id_product']);
        $product->show();
    }
    if (!$i)
        echo "<div>Нет товаров</div>";
    echo "<div class='clearer'></div>";

    view_pages($all, $part, $from);   #вывести список страниц
    ?>



    <script type="text/javascript">                                
        //связываем щелчок по товару с его выделением
        $(".article_item .article_action input").click(function(){ 
            product_toggle_selection($(this).attr("value"));
        });                        
                                        
        $(".article_img img").dblclick(function(){
            //получаем список классов картинки
            var img_class = $(this).parent().attr('class');
            //получаем идентификатор(он является 2м классом)
            var img_id = img_class.split(" ");
            var id = parseInt(img_id[1], 10);
                                                                                                                                                                                                        
            product_toggle(id)
        });                                        
                        
        var is_visible = 0;
                        
        $("#show_hide").click( function(){ 
            is_visible = !is_visible;             
            refresh();
        });
                          
        var is_selected = 0;
        $("#select_deselect").click( function(){ 
            is_selected = !is_selected;             
            refresh_selection();
        });
                               
        refresh();                       
                                        
        function submit_form(){
            $("form[name=page]").submit();
        }
        function set_part(){
            $("form[name=part]").submit();
        }
    </script>
    <?php
}
?>