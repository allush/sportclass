<?php

function p_permission() {
    return array(array("3" => array("write" => "1", "read" => "1")));
}

function p_title() {
    return "Подробная информация о товаре";
}

function p_description() {
    return "Подробная информация о товаре";
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
    ?>
    <style type="text/css">
        form.left{
            float: left;
            margin-right: 4px;
            margin-bottom: 4px;
            padding: 4px;
            width: 174px;
            text-align: center;
            border: 1px solid #e5e5e5;
        }
        form button{
            border: none;
            background: none;
            cursor: pointer;
        }
    </style>
    <div style="clear: both; margin-bottom: 4px; " >Картинки товара <i><b><?php echo "#".$id_product." ".$product->name(); ?></b></i></div> 
    <?php
    $result = mysql_query("SELECT * FROM `product_picture` WHERE `id_product`='$id_product'");
    while ($row = mysql_fetch_array($result)) {
        ?>
        <form name="product_picture<?php echo $row['id_product_picture']; ?>"
        <?php if ($row['cover'] == 1) echo "style='border: 1px solid #3e809d;'" ?>
            class="left" action="product_picture.php" method="post" >
            <input type="hidden" name="id_product" value="<?php echo $id_product; ?>" />
            <input type="hidden" name="id_product_picture" value="<?php echo $row['id_product_picture']; ?>" />
            <input type="hidden" name="referer" value="<?php echo $_SERVER['HTTP_REFERER']; ?>"
            <a href="<?php echo "../img/product/" . $row['picture']; ?>" rel="shadowbox[goods]" title="Увеличить" >
                <img style="max-width: 200px;"src="<?php echo "../img/product/thumbnail/" . $row['picture']; ?>" ><br>
            </a>
            <button onclick="set_cover(<?php echo $row['id_product_picture']; ?>); return false;" name="action" value="cover" ><img title="Сделать обложкой товара" width="16" src="img/cover.png" /></button>
            <button onclick="product_picture_delete(<?php echo $row['id_product_picture']; ?>);return false;" name="action" value="delete" ><img title="Удалить" width="16" src="img/delete.png" /></button>
        </form> 
    <?php } ?>               

    <div style="clear: both; " ></div> 

    <link rel="stylesheet" type="text/css" href="js/jquery.plupload.queue/css/jquery.plupload.queue.css">
    <!-- Load plupload and all it's runtimes and finally the jQuery queue widget --> 
    <script type="text/javascript" src="js/plupload.js"></script>
    <script type="text/javascript" src="js/jquery.plupload.queue/jquery.plupload.queue.js"></script> 

    <div id="html5_uploader"></div>  
    <script type="text/javascript"> 
        // Convert divs to queue widgets when the DOM is ready  
        function plupload_init( id_product ) { 
            // Setup html5 version 
            $("#html5_uploader").pluploadQueue({  
                // General settings  
                runtimes : 'html5',  
                url : 'product_picture_add.php',  
                max_file_size : '10mb',  
                chunk_size : '1mb',  
                unique_names : true,
                filters : [{title : "Image files", extensions : "jpg"}],
                multipart_params:{
                    id_product: id_product
                },
                // Resize images on clientside if we can  
                resize : {width : 1024, height : 682, quality : 100}
            });              
        };  
        plupload_init('<?php echo $id_product; ?>');
    </script>  
    <?php
}
?>