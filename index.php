<?php
session_start();
//error_reporting(E_ALL);
include 'CPage_fe.php';

$p = "main";
if (isset($_GET['p']))
    $p = $_GET['p'];
$page = new CPage_fe($p);
?>
<!DOCTYPE html>
<html>
    <head>
        <?php echo $page->head(); ?>
    </head>

    <body>

        <div id="wrapper" class="width">

            <div id="top" class="block"> 
                <div class="content">
                    <?php echo $page->top(); ?>
                </div>
            </div>

            <div id="main_menu" class="block">
                <div class="content">
                    <?php echo $page->main_menu(); ?>
                </div>
            </div>

            <div id="content" class="block">
                <div class="content">
                    <a id="tbutton" href="#top">&uArr; Наверх &uArr;</a>
                    <div id="left"><?php echo $page->menu(); ?></div>
                    <div id="center">
                        <div id="search">
                            <form name="search" action="index.php?p=search" method="post">
                                <input type="text" name="keyword" placeholder="Поиск" required />
                                <button title="Найти по ключевому слову">Найти</button>
                            </form>
                        </div>
                        <div class="delimiter" style="width: 100%; "></div>

                        <?php echo $page->content(); ?>

                        <div id="product_load"></div>
                    </div>
                    <div class="clearer"></div>
                </div>
            </div>

        </div>
        <div id="spring"></div>
        <div id="footer" class="block">
            <div class="content width">
                <?php echo $page->footer(); ?>
            </div>
        </div>
    </body>
</html>
<?php mysql_close(); ?>
