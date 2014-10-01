<?php

session_start();
include 'CPage_be.php';

$p = "my_profile"; 
if (isset($_GET['p']))
    $p = $_GET['p'];
$page = new CPage_be($p);
?>
<!DOCTYPE html>
<html>
    <head>
        <?php echo $page->head(); ?>
    </head>

    <body>

        <div id="wrapper" class="width">

            <div id="menu" class="block">
                <div class="content">
                    <?php echo $page->menu(); ?>                    
                </div>
            </div>

            <div id="content" class="block">
                <div class="content">
                    <div class='header'><?php echo $page->description(); ?><a style="float: right;" class='small_grey' href='out.php'>Выйти</a></div>
                    <div class='dark_block'><?php echo $page->dark_block(); ?></div>
                    <div class='navigation'><?php echo $page->navigation(); ?></div>
                    <?php echo $page->content(); ?>
                </div>
            </div>

        </div>

    </body>

</html>
<?php mysql_close(); ?>
