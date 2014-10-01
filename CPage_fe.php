<?php
include 'common/CPage.php';

class CPage_fe extends CPage {

    public function __construct($p) {
        parent::__construct($p);
        $this->_load();
    }

    protected function _load() {
        require 'common/connect.php';
        include 'common/CCatalog.php';
        require 'CArticle_fe.php';
        require 'CProduct_fe.php';
        require 'CVideo_fe.php';
        include 'common/CPaginator.php';
        include 'common/CUser.php';
    }

    protected function _title() {
        if (function_exists('p_title'))
            return p_title();
    }

    protected function _head() {
        ?>
        <link rel="icon" type="image/x-icon" href="img/icon.png" />
        <meta http-equiv="Cache-Control" content="no-cache">

        <link rel="stylesheet" type="text/css" href="css/CArticle.css" />
        <link rel="stylesheet" type="text/css" href="css/CProduct.css" />
        <link rel="stylesheet" type="text/css" href="css/CPaginator.css" />
        <link rel="stylesheet" type="text/css" href="css/CVideo.css" />
        <link rel="stylesheet" type="text/css" href="css/CComment.css" />
        <link rel="stylesheet" type="text/css" href="css/general.css" />

        <script type="text/javascript" src="js/jquery.js"></script>
        <script type="text/javascript" src="js/general.js"></script>

        <link rel="stylesheet" type="text/css" href="shadowbox/shadowbox.css">
        <script type="text/javascript" src="shadowbox/shadowbox.js"></script>
        <script type="text/javascript">
            Shadowbox.init({
                skipSetup: true
            });

        </script>

        <script type="text/javascript">
            $(document).ready(function () {
                Shadowbox.setup();
                //                $(window).resize(function() {
                //                    my_resize();
                //                });
                //                my_resize();
                //                setInterval(my_resize, 500);
            });
            //            $(window).scroll(function(){
            //                tbutton();
            //            })
        </script>
        <?php
    }

    public function top() {
        ?>
        <a href="/">
            <img title="На главную" src="img/head.png" />
        </a>
        <?php
    }

    public function main_menu() {
        ?>
        <ul>
            <li><a href="index.php?p=products">КАТАЛОГ</a></li>
            <li><a href="index.php?p=about">О КОМПАНИИ</a></li>
            <li><a href="index.php?p=club">СПОРТИВНЫЙ КЛУБ</a></li>
            <li><a href="index.php?p=delivery">ДОСТАВКА</a></li>
            <li><a href="index.php?p=contacts">КОНТАКТЫ</a></li>
            <li><a href="/forum/">ФОРУМ</a></li>
        </ul>
        <?php
    }

    private function create_menu($id = 0, $margin = 0) {
        $result = mysql_query("SELECT * FROM `catalog` WHERE `parent`='$id' ORDER BY `name` ASC");

        if (!mysql_num_rows($result))
            return;

        echo "<ul class='catalogList'>";
        while ($row = mysql_fetch_array($result)) {
            echo "<li><img title='Развернуть' src='img/arrow.png'>";

            echo "<a id='catalog" . $row['id_catalog'] . "' href='index.php?p=products&catalog=" . $row['id_catalog'] . "'>" . $row['name'] . "</a>";



            $margin += 15;
            $this->create_menu($row['id_catalog'], $margin);
            $margin -= 15;
            echo "</li>";
        }
        echo "</ul>";
    }

    protected function headings() {

        $result = mysql_query("SELECT * FROM `heading` WHERE `onMain`='1' ORDER BY `name` ASC");

        if (!mysql_num_rows($result))
            return;

        echo "<ul>";
        while ($row = mysql_fetch_array($result)) {
            echo "<li><img title='Развернуть' src='img/arrow.png'><a id='heading" . $row['id_heading'] . "' href='index.php?p=articles&id_heading=" . $row['id_heading'] . "'>" . $row['name'] . "</a>";

            echo "<ul>";
            $resultArticle = mysql_query("SELECT * FROM `article` WHERE `id_heading`='" . $row['id_heading'] . "'");
            while ($rowArticle = mysql_fetch_array($resultArticle)) {
                $article = new CArticle_fe($rowArticle['id_article']);
                echo "<li><img title='Развернуть' src='img/arrow.png'><a id='article" . $rowArticle['id_article'] . "' href='index.php?p=article&id=" . $rowArticle['id_article'] . "'>" . $article->title() . "</a></li>";
            }
            echo "</ul>";

            echo "</li>";
        }
        echo "</ul>";
    }

    protected function _menu() {
        ?>
        <p class="heading">КАТЕГОРИИ</p>

        <p class="delimiter"></p>

        <?php
        $this->create_menu(0, 15);
        ?>

        <p class="delimiter" style="margin-top: 24px;"></p>
        <?php
        $this->headings();
        ?>


        <div id="brand">
            <img src="img/brand/swix.gif" alt="swix">
            <img src="img/brand/craft.gif" alt="craft">
            <img src="img/brand/fischer.gif" alt="fischer" >
            <img src="img/brand/noname.gif" alt="noname">
            <img src="img/brand/atomic.gif" alt="atomic">
            <img src="img/brand/oneway.gif" alt="oneway">
            <img src="img/brand/toko.gif" alt="toko">
            <img src="img/brand/rossignol.gif" alt="rossignol">
            <img src="img/brand/start.gif" alt="start">
        </div>

        <script type="text/javascript">

            $("#left ul li ul").hide();


            function expand(obj){
                if(obj.attr('id') == "left")
                    return;
                obj.parent().show();
                var parent = obj.parent()
                expand(parent);
            }

        <?php
        if ($_GET['p'] == "products" && isset($_GET['catalog']) && is_numeric($_GET['catalog'])) {
            ?>
                    expand($("a#catalog<?php echo $_GET['catalog']; ?>"));
                    $("a#catalog<?php echo $_GET['catalog']; ?>").parent().children("ul").show();
            <?php
        } elseif ($_GET['p'] == "product" && isset($_GET['id']) && is_numeric($_GET['id'])) {
            $product = new CProduct_fe($_GET['id']);
            $catalogs = $product->catalogs();
            foreach ($catalogs as $catalog) {
                ?>
                            expand($("a#catalog<?php echo $catalog->id_catalog(); ?>"));
                            $("a#catalog<?php echo $catalog->id_catalog(); ?>").parent().children("ul").show();
                            $("#left ul li a#catalog<?php echo $catalog->id_catalog(); ?>").addClass("active");
                <?php
            }
        } elseif ($_GET['p'] == "article" && isset($_GET['id']) && is_numeric($_GET['id'])) {
            ?>
                    expand($("a#article<?php echo $_GET['id']; ?>"));
                    $("a#article<?php echo $_GET['id']; ?>").parent().children("ul").show();
            <?php
        } elseif ($_GET['p'] == "articles" && isset($_GET['id_heading']) && is_numeric($_GET['id_heading'])) {
            ?>
                    expand($("a#heading<?php echo $_GET['id_heading']; ?>"));
                    $("a#heading<?php echo $_GET['id_heading']; ?>").parent().children("ul").show();
                    $("#left ul li a#heading<?php echo $_GET['id_heading']; ?>").addClass("active");
            <?php
        }
        ?>
            $("#left ul li img").click(function(){
                $(this).parent().children("ul").toggle(200,
                function(){
                    if($(this).parent().children("ul").is(":visible")){
                        $(this).parent().children("img").attr("src","img/arrow_rotate.png");
                        $(this).parent().children("img").attr("title","Свернуть");
                    }
                    else{
                        $(this).parent().children("img").attr("src","img/arrow.png");
                        $(this).parent().children("img").attr("title","Развернуть");
                    }
                });


            })



            function ss(){
                $("#left ul").is(":visible")
            }

            $("#left ul li").each(function (index, elem) {
                if(!$(elem).children("ul").length){
                    $(elem).find("img").hide();
                    $(elem).css("margin-left","14px");
                }
            });

            $("#left ul li a#article<?php echo $_GET['id'] ?>").addClass("active");
            $("#left ul li a#catalog<?php echo $_GET['catalog'] ?>").addClass("active");
        </script>
        <?php
    }

    protected function _footer() {
        ?>
        <table>
            <tr>
                <td id="footer_l">
                    <img style="float: left; margin-right: 8px;"src="img/logo_grey.png" />
                    <br>+7 (8452) 77-57-40 <br>info@sportclass.com.ru
                </td>

                <td id="footer_c">2013 &copy; ВСЕ ПРАВА ЗАЩИЩЕНЫ
			<?php echo file_get_contents('http://allush.github.io/developed.html'); ?>
		</td>

                <td id="footer_r">
                    <a href="" target="_blank"><img title="" src="img/vk.png" /> </a>
                    <a href="" target="_blank"><img title="" src="img/facebook.png" /> </a>
                    <a href="" target="_blank"><img title="" src="img/twitter.png" /> </a>
                </td>
            </tr>
        </table>
        <?php
    }

}
?>
