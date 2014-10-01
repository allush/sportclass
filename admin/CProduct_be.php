<?php
include '../common/CProduct.php';

class CProduct_be extends CProduct {

    public function __construct($id_product) {
        parent::__construct($id_product);
    }

    /**
     * Функция создает пустой объект товара
     */
    public static function create() {
        if (!mysql_query("INSERT INTO `product`(`date_add`, `id_product_unit`) VALUES(NOW(),'1')"))
            return false;

        $result = mysql_query("SELECT MAX(`id_product`) FROM `product`");
        if (!$result)
            return false;

        $row = mysql_fetch_array($result);

        wlog(1, "Создал новый продукт ");

        return new CProduct_be($row[0]);
    }

    public static function join($ids) {
        $name = "";
        $description = "";
        $pictures = array();


        foreach ($ids as $id) {
            $product = new CProduct_be($id);
            $name .= $product->name();
            $description .= $product->description();
            foreach ($product->product_picture() as $picture)
                array_push($pictures, $picture);

            $a = mysql_query("DELETE FROM `product_catalog` WHERE `id_product`='" . $product->id_product() . "'");
            $a = mysql_query("DELETE FROM `product_picture` WHERE `id_product`='" . $product->id_product() . "'");
            $a = mysql_query("DELETE FROM `product` WHERE `id_product`='" . $product->id_product() . "'");
        }

        $mainProduct = CProduct_be::create();
        $mainProduct->set_name($name);
        $mainProduct->set_description($description);

        foreach ($pictures as $picture) {
            $a = mysql_query("INSERT INTO `product_picture`(`id_product`, `picture`) VALUES('" . $mainProduct->id_product() . "', '$picture')");
        }
        return $mainProduct;
    }

    protected function picture() {
        return "../img/product/" . $this->get_picture();
    }

    protected function thumbnail() {
        return "../img/product/thumbnail/" . $this->get_picture();
    }

    public function set_name($name) {
        if (!$this->id_product)
            return false;

        if ($this->name == $name)
            return true;

        if (mysql_query("UPDATE `product` SET `name`='$name', `date_last_edit`=NOW() WHERE `id_product`='$this->id_product'")) {
            wlog(1, "Изменено название товара id=" . $this->id_product . "  с " . $this->name . " на " . $name);
            $this->name = $name;
            return true;
        }
        return false;
    }

    public function set_description($description) {
        if (!$this->id_product)
            return false;

        if ($this->description == $description)
            return true;

        if (mysql_query("UPDATE `product` SET `description`='$description', `date_last_edit`=NOW() WHERE `id_product`='$this->id_product'")) {
            wlog(1, "Изменено описание товара id=" . $this->id_product . " '" . $this->name . "' с " . $this->description . " на " . $description);
            $this->description = $description;
            return true;
        }
        return false;
    }

    public function set_existence($existence) {

        if (!$this->id_product)
            return false;

        if (!is_numeric($existence))
            return false;

        if ($this->existence == $existence)
            return true;

        settype($existence, "int");

        if (mysql_query("UPDATE `product` SET `existence`='$existence', `date_last_edit`=NOW() WHERE `id_product`='$this->id_product'")) {

            wlog(1, "Изменено количество товара id=" . $this->id_product . " '" . $this->name . "' с " . $this->existence . " на " . $existence);

            $this->cost = $cost;
            return true;
        }
        return false;
    }

    public function set_cost($cost) {
        if (!$this->id_product)
            return false;

        if (!is_numeric($cost))
            return false;

        if ($this->cost == $cost)
            return true;

        settype($cost, "int");

        if (mysql_query("UPDATE `product` SET `cost`='$cost', `date_last_edit`=NOW() WHERE `id_product`='$this->id_product'")) {

            wlog(1, "Изменена стоимость товара id=" . $this->id_product . " '" . $this->name . "' с " . $this->cost . " на " . $cost);

            $this->cost = $cost;
            return true;
        }
        return false;
    }

    public function set_discount($discount) {
        if (!$this->id_product)
            return false;

        if (!is_numeric($discount))
            return false;

        if ($this->discount == $discount)
            return true;

        settype($discount, "int");

        if (mysql_query("UPDATE `product` SET `discount`='$discount', `date_last_edit`=NOW() WHERE `id_product`='$this->id_product'")) {
            wlog(1, "Изменена скидка на товар id=" . $this->id_product . "  " . $this->name . " с " . $this->discount . " на " . $discount);
            $this->discount = $discount;
            return true;
        }
        return false;
    }

    public function set_id_product_unit($id_product_unit) {
        if (!$this->id_product)
            return false;

        if (!is_numeric($id_product_unit))
            return false;

        if ($this->id_product_unit == $id_product_unit)
            return true;

        settype($id_product_unit, "int");

        if (mysql_query("UPDATE `product` SET `id_product_unit`='$id_product_unit', `date_last_edit`=NOW() WHERE `id_product`='$this->id_product'")) {
            wlog(1, "Изменил единицу измерения товара  id=" . $this->id_product . "  " . $this->name . " с " . $this->id_product_unit . " на " . $id_product_unit);
            $this->id_product_unit = $id_product_unit;
            return true;
        }
        return false;
    }

    public function set_visible($visible) {
        if (!$this->id_product)
            return false;

        if (isset($visible) && ($visible == "true" || $visible == "on"))
            $visible = 1;
        else
            $visible = 0;

        if ($this->visible == $visible)
            return true;

        if (mysql_query("UPDATE `product` SET `visible`='$visible', `date_last_edit`=NOW() WHERE `id_product`='$this->id_product'")) {
            wlog(1, "Изменил свойство 'Показывать' товара  id=" . $this->id_product . "  " . $this->name . " с " . $this->visible . " на " . $visible);
            $this->visible = $visible;
            return true;
        }
        return false;
    }

    public function add_picture($fileName) {

//        $original = "../img/product/original/" . $fileName;
        $filePath = "../img/product/" . $fileName;

        //копируем оригинал изображения в отдельную папку
//        copy($filePath, $original);
        $type = getimagesize($filePath);

        $image = 0;
        switch ($type[2]) {
            case 2:
                $image = imagecreatefromjpeg($filePath);
                break;
            case 3:
                $image = imagecreatefrompng($filePath);
                break;
        }

        if (!$image) {
            if (file_exists($filePath))
                unlink($filePath);
            return 0;
        }

//Добавление миниатюры товара
        thumbnail($image, "../img/product/thumbnail/" . $fileName, 216);

//накладываем логотип
//        logo($image);
        imagejpeg($image, $filePath, 100);

        mysql_query("INSERT INTO `product_picture`(`id_product`, `picture`) VALUES( $this->id_product , '$fileName')");
        mysql_query("UPDATE `product` SET `date_last_edit`=NOW() WHERE `id_product`='$this->id_product'");
        wlog(1, "Добавлена новая картинка к товару id=" . $this->id_product . " '" . $this->name . "'");
    }

    /**
     * принимает  идентификаторов каталога, в которых данный продукт будет размещен
     */
    public function set_product_catalog($id_catalog) {
        if (!$this->id_product)
            return false;

        if (!is_numeric($id_catalog))
            return false;


        if (!mysql_query("INSERT INTO `product_catalog`(`id_product`, `id_catalog`) VALUES ('$this->id_product','$id_catalog')"))
            return false;

        wlog(1, "Товар " . $this->name . " распределен в каталог " . $id_catalog);
        return true;
    }

    public function set_cover($id_product_picture) {
        mysql_query("UPDATE `product_picture` SET `cover`='0' WHERE `id_product`='$this->id_product'");
        mysql_query("UPDATE `product_picture` SET `cover`='1' WHERE `id_product`='$this->id_product' AND `id_product_picture`='$id_product_picture'");
        mysql_query("UPDATE `product` SET `date_last_edit`=NOW() WHERE `id_product`='$this->id_product'");
        wlog(1, "Изменена обложка товара " . $this->name);
    }

    public function delete_picture($id_product_picture) {

//получаем картинку связанную с удаляемым товаром
        $result = mysql_query("SELECT * FROM `product_picture` WHERE `id_product_picture`='$id_product_picture'");
        if (!$result)
            return false;

        $row = mysql_fetch_array($result);
        $fname = "../img/product/" . $row['picture'];
        $thumb = "../img/product/thumbnail/" . $row['picture'];

        if (file_exists($thumb) && is_file($thumb))
            unlink($thumb);

        if (file_exists($fname) && is_file($fname))
            unlink($fname);

        if (!mysql_query("DELETE FROM `product_picture` WHERE `id_product_picture`='$id_product_picture'"))
            return false;
        wlog(1, "Удалена картинка товара " . $this->name);
    }

    public function delete() {
        mysql_query("UPDATE `product` SET `deleted`='1' WHERE `id_product`='$this->id_product'");
        wlog(1, "Удален товар '" . $this->name . "'");
    }

    public function deleteComplete() {

//получаем имена всех картинок, связанных с удаляемым товаром
        $result = mysql_query("SELECT * FROM `product_picture` WHERE `id_product`='$this->id_product'");

        while ($row = mysql_fetch_array($result)) {
            $picture = "../img/product/" . $row['picture'];
            $thumbnail = "../img/product/thumbnail/" . $row['picture'];

            if (file_exists($picture))
                unlink($picture);

            if (file_exists($thumbnail))
                unlink($thumbnail);

        }

        mysql_query("DELETE FROM `product_picture`  WHERE `id_product`='$this->id_product'") or die(mysql_error());
        mysql_query("DELETE FROM `product_catalog`  WHERE `id_product`='$this->id_product'") or die(mysql_error());
        mysql_query("DELETE FROM `product`          WHERE `id_product`='$this->id_product'") or die(mysql_error());

        wlog(1, "Удален полностью товар '" . $this->name . "'");
    }

    public function undelete() {
        mysql_query("UPDATE `product` SET `deleted`='0' WHERE `id_product`='$this->id_product'");
        wlog(1, "Восстановлен товар '" . $this->name . "'");
    }

    // выводит список каталогов и отмечает каталоги, в которых состоит товар
    private function catalogList($id = 0, $margin = 0) {
        $res = mysql_query("SELECT * FROM `catalog` WHERE `parent`='$id'");
        while ($row = mysql_fetch_array($res)) {
            $id_catalog = $row['id_catalog'];
            $name = $row['name'];
            ?>
            <div style='margin-left: <?php echo $margin . "px"; ?>'>
                <p>
                    <input id="catalog<?php echo $id_catalog; ?>"  
            <?php if (mysql_num_rows(mysql_query("SELECT * FROM `product_catalog` WHERE `id_catalog`='$id_catalog' AND `id_product`='$this->id_product'"))) echo "checked='yes'"; ?>  
                           name="id_catalog[]" type="checkbox" value="<?php echo $id_catalog; ?>" />
                    <label for="catalog<?php echo $id_catalog; ?>"><?php echo $name; ?></label>
                </p>
            </div>

            <?php
            $margin +=16;
            $this->catalogList($id_catalog, $margin);
            $margin -=16;
        }
    }

    public function show($i = 0) {
        ?>
        <div class="article_item" id="<?php echo $this->id_product; ?>">

            <form action="product_edit.php" method="post" name="form<?php echo $this->id_product; ?>" onsubmit="return false;">
                <div class="article_img <?php echo $this->id_product; ?>">
                    <p class="id_product">#<?php echo $this->id_product . " " . $this->name; ?></p>
                    <div class="load" id="load<?php echo $this->id_product; ?>" ></div>
                    <img alt="" title="" src="<?php echo $this->thumbnail(); ?>" >

                    <div class="article_action">
                        <input type="checkbox" name="id_product" value="<?php echo $this->id_product; ?>"/>
                        <a onclick="price_tag_add(<?php echo $this->id_product; ?>);return false;" href="price_tag_add.php?id_product=<?php echo $this->id_product; ?>" >   <img  title="Добавить в список ценников" alt="" src="img/price_tag.png">  </a>
                        <a onclick="save_form(<?php echo $this->id_product; ?>); " href="index.php?p=product_picture&id_product=<?php echo $this->id_product; ?>" > <img title="Редактировать картинки товара" alt="" src="img/edit_picture.png"> </a>
                        <a onclick="save_form(<?php echo $this->id_product; ?>); return false;"> <img title="Сохранить" alt="" src="img/save.png"> </a>
                        <?php if (!$this->deleted) { ?>
                            <a onclick="if(!confirm('Вы действительно хотите удалить товар?')) return false; delete_form(<?php echo $this->id_product; ?>); return false;" href="product_delete.php?id_product=<?php echo $this->id_product; ?>" > <img title="Удалить" alt="" src="img/delete.png"> </a>
                        <?php } else { ?>
                            <a onclick="undelete_form(<?php echo $this->id_product; ?>); return false;"> <img title="Восстановить" alt="" src="img/undelete.png"> </a>
                            <a onclick="deleteComplete_form(<?php echo $this->id_product; ?>); return false;"> <img title="Удалить окончательно" alt="" src="img/delete.png"> </a>
        <?php } ?>
                    </div>
                </div> 


                <div class="info">

                    <div class="article_info1" >
                        <div class="article_name">
                            <input name="name" type="text" value="<?php echo $this->name; ?>"/>
                        </div>

                        <table>
                            <tr><td>Цена, руб.</td><td><input name="cost" size="5" type="number" value="<?php echo $this->cost; ?>" /> </td></tr>
                            <tr><td>В наличии, ед.</td><td><input name="existence"  placeholder="0" size="5" type="text" value="<?php echo (string) $this->existence; ?>"/></td></tr>
                            <tr>
                                <td>Скидка, %</td>
                                <td><input name="discount" size="5" type="number" value="<?php echo $this->discount; ?>"/></td></tr>
                            </tr>

                            <tr>
                                <td>Ед. измерения:</td>
                                <td>
                                    <select name="id_product_unit">
                                        <?php
                                        $result = mysql_query("SELECT * FROM `product_unit`");
                                        if ($result) {
                                            while ($row = mysql_fetch_array($result)) {
                                                echo "<option value='" . $row['id_product_unit'] . "'";
                                                if ($row['id_product_unit'] == $this->id_product_unit)
                                                    echo "selected='yes'";
                                                echo ">" . $row['name'] . "</option>";
                                            }
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>

                            <tr>
                                <td>Показывать</td>
                                <td><input name="visible"
                                    <?php
                                    if ($this->visible) {
                                        echo "checked='yes'";
                                    }
                                    ?> type="checkbox"/> </td>
                            </tr>
                        </table>
                    </div>

                    <div class="article_info2">
                        <?php
                        $this->catalogList();
                        ?>

                    </div>
                    <div class="clearer"></div>
                    <div class="article_description">
                        <textarea name="description"><?php echo $this->description; ?></textarea>
                    </div>
                </div>
            </form>
            <div class="clearer"></div>
        </div>

        <?php
    }

}
?>
