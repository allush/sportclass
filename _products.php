<?php

function p_title() {
    return "Каталог товаров для спорта, одежда, спортивный инвентарь в Саратове. Спортивный-экипировочный центр Sport Class";
}

function p_description() {
    return "Каталог товаров для спорта, одежда, спортивный инвентарь в Саратове. Спортивный-экипировочный центр Sport Class";
}

function p_breadcrumbs() {
    $breadcrumbs = "<a href='index.php?p=products'>Каталог</a>";
    if (isset($_GET['catalog'])) {
        $catalog = new CCatalog($_GET['catalog']);
        $parents_name = "";
        $parents_name = $catalog->parents_name();
        $parents_name[] = $catalog->name();
        $parents_name = implode(" | ", $parents_name);

        if (strlen($parents_name))
            $breadcrumbs .= " | " . $parents_name;
    }
    return $breadcrumbs;
}

function p_content() {

    $id_catalog = 0;
    $children = "";
    if (isset($_GET['catalog'])) {
        $id_catalog = (int) $_GET['catalog'];

        $catalog = new CCatalog($id_catalog);

        $children = array();
// Получить всех потомков данного каталога, чтобы выбрать все товары из них
        $children = $catalog->children();
//добавить сам каталог в список, чтобы товары выбирались и из него тоже
        array_push($children, $id_catalog);
        $children = implode(",", $children);
        $children = " WHERE `id_catalog` IN ($children) ";
    }


    $from = 0;
    if (isset($_GET['from']) && is_numeric($_GET['from'])) {
        $from = (int) $_GET['from'];
    }
    $part = 21;

// Выводим товары этого каталога и всех подкаталогов
    $query = "
    SELECT 
    `id_product` 
    FROM 
    `product`  
    WHERE 
    $user 
    `id_product` IN  ( SELECT `id_product` FROM `product_catalog` $children GROUP BY `id_product`) AND 
    `visible`='1' AND 
    `deleted`='0' 
    ORDER BY 
    `date_last_edit` DESC";

    $result = mysql_query($query . " LIMIT $from,$part ") or die(mysql_error());
    if (!$result)
        return;

    $i = 1;
    while ($row = mysql_fetch_array($result)) {
        $product = new CProduct_fe($row['id_product']);
        $product->show($i++);
    }
    if($i == 1)
        echo "Данный каталог пока пуст";
    ?>

    <?php
    $result = mysql_query($query);
    if (!$result)
        return;
    $all = mysql_num_rows($result);

    CPaginator::show($all, $from, $part);
    ?>



    <!--    <script type="text/javascript">
            var num_default_loaded_products = 21;
            $(window).scroll(function(){
                tbutton();
                load_products('<?php echo $_GET['catalog']; ?>');
            })        
        </script>-->
    <?php
}
?>