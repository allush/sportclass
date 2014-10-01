<?php
include_once 'CCatalog.php';

abstract class CProduct {  //Класс товар

    protected $id_product = false;
    protected $name = false;
    protected $description = false;
    protected $date_add = false;
    protected $date_last_edit = false;
    protected $cost = false;
    protected $existence = false;
    protected $discount = false;
    protected $id_product_unit = false;
    protected $visible = false;
    protected $deleted = false;
    protected $catalogs = array();
    protected $product_picture = array();

    public function __construct($id_product) {
        if (!$id_product)
            return;

        $result = mysql_query("SELECT * FROM `product` WHERE `id_product`='$id_product'");
        if (!$result)
            return;

        $row = mysql_fetch_array($result);
        $this->id_product = $id_product;
        $this->name = $row['name'];
        if (!strlen($this->name))
            $this->name = "Нет названия";
        $this->description = $row['description'];
        $this->date_add = $row['date_add'];
        $this->date_last_edit = $row['date_last_edit'];
        $this->cost = $row['cost'];
        $this->existence = $row['existence'];
        $this->discount = $row['discount'];
        $this->id_product_unit = $row['id_product_unit'];
        $this->visible = $row['visible'];
        $this->deleted = $row['deleted'];

        $result = mysql_query("SELECT * FROM `product_catalog` WHERE `id_product`='$id_product'");
        if (!$result)
            return;
        while ($row = mysql_fetch_array($result))
            array_push($this->catalogs, new CCatalog($row['id_catalog']));


        $result = mysql_query("SELECT * FROM `product_picture` WHERE `id_product`='$id_product' ORDER BY `id_product_picture` DESC");
        if (!$result)
            return;
        while ($row = mysql_fetch_array($result))
            array_push($this->product_picture, $row['picture']);
    }

    
    public function catalogs() {
        return $this->catalogs;
    }

    public function product_picture() {
        return $this->product_picture;
    }

    public function id_product() {
        return $this->id_product;
    }

    public function name() {
        return html_entity_decode($this->name);
    }

//возвращает имя главной картинки товара
    protected function get_picture() {
        $result = mysql_query("SELECT * FROM `product_picture` WHERE `id_product`='$this->id_product' AND `cover`='1'");
        if (!$result)
            return false;

        if (!mysql_num_rows($result))
            $result = mysql_query("SELECT * FROM `product_picture` WHERE `id_product`='$this->id_product' ORDER BY `id_product` DESC LIMIT 1");

        if (!$result)
            return false;

        $row = mysql_fetch_array($result);
        if (!$row)
            return "no_photo.jpg";

        return $row['picture'];
    }

    abstract protected function picture();
    abstract protected function thumbnail();

    public function date_add() {
        return $this->date_add;
    }

    public function date_last_edit() {
        return $this->date_last_edit;
    }

    public function cost() {
        return $this->cost;
    }

    public function description() {
        return html_entity_decode($this->description);
    }

    public function existence() {
        return $this->existence;
    }

    public function discount() {
        return $this->discount;
    }

    public function visible() {
        return $this->visible;
    }

    public function product_unit_id() {
        return $this->id_product_unit;
    }

    public function product_unit_name() {
        $result = mysql_query("SELECT `name` FROM `product_unit` WHERE `id_product_unit`='$this->id_product_unit'");
        if (!$result)
            return false;
        $row = mysql_fetch_array($result);
        return $row['name'];
    }

    abstract protected function show($i = 0);
}

?>