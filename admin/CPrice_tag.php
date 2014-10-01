<?php

/**
 * Класс ценник
 *
 * @author Alexey
 */
class CPrice_tag {

    private $id_product;
    private $shop_name; // ИП Лушникова Е.И.
    private $product_name;
    private $cost;
    private $unit;
    private $date; //Дата генерации ценника
    private $signature;

    public function __construct($id_product) {

        if (!$id_product)
            return;
        $this->id_product = $id_product;

        $product = new CProduct_be($this->id_product);
        $this->shop_name = iconv("UTF-8", "EUC-JP", "ИП Лушникова Е.И.");
        $this->date = time();

        $this->product_name = iconv("UTF-8", "EUC-JP", $product->name());
        $this->cost = $product->cost() . ".00 " . iconv("UTF-8", "EUC-JP", "");
        $this->unit = iconv("UTF-8", "EUC-JP", "Цена в рублях за:  1 " . $product->product_unit_name());
        $this->signature = iconv("UTF-8", "EUC-JP", "Подпись") . ": ___________________ " . @date("d.m.Y", $this->date);
    }

    private function center_x($text, $font, $font_size, $src_w) {
        $arr = imagettfbbox($font_size, 0, $font, $text);

        $box_w = $arr[2] - $arr[0];
        return $center_x = ($src_w - $box_w) / 2;
    }

    public function image() {
        $src_w = 709;
        $src_h = 472;

        $img = imagecreatetruecolor($src_w, $src_h);
        $color_light = imagecolorallocate($img, 241, 228, 216);
        imagefilledrectangle($img, 0, 0, $src_w - 1, $src_h - 1, $color_light);

        $font_b = "font/georgia_b.ttf";

        $color_dark = imagecolorallocate($img, 89, 71, 53);
        imagefilledrectangle($img, 0, 0, $src_w - 1, 40, $color_dark);

        imagettftext($img, 20, 0, $this->center_x($this->shop_name, $font_b, 20, $src_w), 30, $color_light, $font_b, $this->shop_name);
        imagettftext($img, 36, 0, $this->center_x($this->product_name, $font_b, 36, $src_w), 120, $color_dark, $font_b, $this->product_name);
        imagettftext($img, 60, 0, $this->center_x($this->cost, $font_b, 60, $src_w), 260, $color_dark, $font_b, $this->cost);
        imagettftext($img, 24, 0, 20, 340, $color_dark, $font_b, $this->unit);
        imagettftext($img, 20, 0, 20, 440, $color_dark, $font_b, $this->signature);

        return $img;
    }

    public function delete() {
        return mysql_query("DELETE FROM `price_tag` WHERE `id_product`='$this->id_product'");
    }

}

?>
