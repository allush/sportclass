<?php

function p_permission() {
    return array(array("3" => array("write" => "1", "read" => "1")));
}

function p_title() {
    return "Распределение товара по каталогам";
}

function p_description() {
    return "Распределение товара по каталогам";
}

function p_dark_block() {
    ?>
    <a href="<?php echo $_SERVER['HTTP_REFERER']."#".$_GET['id_product']; ?>">Назад</a>
    <?php
}

function p_content() {
    if (!isset($_GET['id_product']))
        return;
    $id_product = $_GET['id_product'];

    $product = new CProduct_be($id_product);
    echo  "<p>Товар: <b><i>#".$id_product." ".$product->name()."</i></b></p>";
    echo "<p><img src='" . $product->thumbnail() . "' /></p>";

    function catalogs($id_product, $id = 0, $margin = 0) {
        $res = mysql_query("SELECT * FROM `catalog` WHERE `parent`='$id'");
        while ($row = mysql_fetch_array($res)) {
            $id_catalog = $row['id_catalog'];
            $name = $row['name'];
            ?>
            <div style='margin-left: <?php echo $margin . "px"; ?>'>
                <p>
                    <input id="catalog<?php echo $id_catalog; ?>"  
                           <?php if (mysql_num_rows(mysql_query("SELECT * FROM `product_catalog` WHERE `id_catalog`='$id_catalog' AND `id_product`='$id_product'"))) echo "checked='yes'"; ?>  
                           name="id_catalog[]" type="checkbox" value="<?php echo $id_catalog; ?>" />
                    <label for="catalog<?php echo $id_catalog; ?>"><?php echo $name; ?></label>
                </p>
            </div>

            <?php
            $margin +=16;
            catalogs($id_product, $id_catalog, $margin);
            $margin -=16;
        }
    }
    ?>
    <form action="product_catalog.php" method="post">
        <input type="hidden" name="id_product" value="<?php echo $id_product; ?>" />
        <input type="hidden" name="referer" value="<?php echo $_SERVER['HTTP_REFERER']; ?>" />
        <?php
        catalogs($id_product);
        ?>
        <input type="submit" value="Сохранить" />
    </form>
    <?php
}
?>