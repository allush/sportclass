<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CProduct_fe
 *
 * @author Alexey
 */
include 'common/CProduct.php';

class CProduct_fe extends CProduct {

    public function __construct($id_product) {
        parent::__construct($id_product);
    }

    public function picture() {
        return "img/product/" . $this->get_picture();
    }

    public function thumbnail() {
        return "img/product/thumbnail/" . $this->get_picture();
    }

    public function show($i = 0) {
        ?>
        <div class="product_item" <?php if (!($i % 3)) echo "style='margin-right: 0;'" ?> id="product_id_<?php echo $this->id_product; ?>">            

            <div class="product_img" onclick="location='index.php?p=product&id=<?php echo $this->id_product; ?>'">
                <a href="index.php?p=product&id=<?php echo $this->id_product; ?>">
                    <img alt="" title="" src="<?php echo $this->thumbnail(); ?>">
                </a>
            </div>

            <div class="product_name">
                <a href="index.php?p=product&id=<?php echo $this->id_product; ?>"><?php echo $this->name; ?></a>
            </div>   
        </div> 
        <?php
    }

    public function linkedVideos() {
        $catalogs = array();
        foreach ($this->catalogs() as $catalog) {
            $catalogs[] = $catalog->id();
            foreach ($catalog->parents_id() as $id_catalog)
                $catalogs[] = $id_catalog;
        }
        array_unique($catalogs);
        $catalogs = implode(",", $catalogs);

        $result = mysql_query("SELECT * FROM `video` WHERE `id_heading` IN (SELECT `id_heading` FROM `catalog_heading` WHERE `id_catalog` IN ($catalogs)) ORDER BY `id_video` DESC LIMIT 0,1");
        if (!mysql_num_rows($result))
            return 0;
        ?>
        <div class="heading">Видео по теме</div>
        <?php
        echo "<ul>";
        while ($row = mysql_fetch_array($result)) {
            $video = new CVideo_fe($row['code']);
            $video->show();
        }
        echo "</ul>";
    }

    public function linkedArticles() {
        $catalogs = array();
        foreach ($this->catalogs() as $catalog) {
            $catalogs[] = $catalog->id();
            foreach ($catalog->parents_id() as $id_catalog)
                $catalogs[] = $id_catalog;
        }
        array_unique($catalogs);
        $catalogs = implode(",", $catalogs);

        $result = mysql_query("SELECT * FROM `article` WHERE `id_heading` IN (SELECT `id_heading` FROM `catalog_heading` WHERE `id_catalog` IN ($catalogs)) ORDER BY `date` DESC LIMIT 0,3");
        if (!mysql_num_rows($result))
            return 0;
        ?>
        <div class="heading">Статьи по теме</div>
        <?php
        echo "<ul>";
        while ($row = mysql_fetch_array($result)) {
            $article = new CArticle_fe($row['id_article']);
            echo "<li><a href='index.php?p=article&id=" . $article->id() . "'>" . $article->title() . "</a></li>";
        }
        echo "</ul>";
    }

    public function card() {
        ?>
        <div class="product_card">
            <div class="product_name heading"><?php echo $this->name; ?></div>
            <div class="product_card_img">
                <a href="<?php echo $this->picture(); ?>" rel="shadowbox[pics]">
                    <img src="<?php echo $this->picture(); ?>" />
                </a>
                <div class="product_cost">
                    <p class="subheading">Цена: <?php echo $this->cost() . " р."; ?></p>
                </div> 
            </div>
            <div class="product_info">
                <div class="product_decription">
                    <p class="subheading">Описание:</p>
                    <?php echo $this->description; ?>
                </div>


            </div>

            <div class="clearer"></div>
            <div class="delimiter" style="margin-bottom: 16px; width: 100%;"></div>

            <!--            <div class="product_video">
            <?php $this->linkedVideos(); ?>
            
                        </div>
            
                        <div class="product_article">
            <?php $this->linkedArticles(); ?>
                        </div>
            
                        <div class="delimiter" style="margin-bottom: 16px; width: 100%;"></div>-->

            <div class="heading">ВСЕ КАРТИНКИ ТОВАРА</div>  
            <?php
            foreach ($this->product_picture as $picture) {
                ?>
                <a href="<?php echo "img/product/" . $picture; ?>" rel="shadowbox[pics]">
                    <img style="max-width: 216px; max-height: 174px;"alt="" src="<?php echo "img/product/thumbnail/" . $picture; ?>"/> 
                </a>
                <?php
            }
            ?>

            <div class="byWith">        <div class='heading'>С ЭТИМ ТОВАРОМ ПОКУПАЮТ</div>
                <?php
                $i = 1;
                $result = mysql_query('SELECT * FROM `product` WHERE `id_product` <> '.$this->id_product.'  ORDER BY RAND() LIMIT 0,3') or die(mysql_error());
                while ($row = mysql_fetch_array($result)) {
                    $product = new CProduct_fe($row['id_product']);
                    $product->show($i++);
                }
                ?>
            </div>

            <div class="clearer"></div>
            <!-- Put this script tag to the <head> of your page -->
            <script type="text/javascript" src="//vk.com/js/api/openapi.js?63"></script>

            <script type="text/javascript">
                VK.init({apiId: 3237807, onlyWidgets: true});
            </script>

            <!-- Put this div tag to the place, where the Comments block will be -->
            <div id="vk_comments"></div>
            <script type="text/javascript">
                VK.Widgets.Comments("vk_comments", {limit: 10, width: "496", attach: "*"});
            </script>
            <div class="clearer"></div>
        </div>
        <?php
    }

}
?>
