<?php

function p_permission() {
    return array(array("3" => array("write" => "1", "read" => "1")));
}

function p_title() {
    return "Добавление нового товара";
}

function p_description() {
    return "Добавление нового товара";
}

function p_dark_block() {
    ?>
    <a href='index.php?p=product'>Все товары</a>
    <a href="index.php?p=product_filter">Поиск</a>
    <?php
    $user = new CUser($_SESSION['id_user']);
    if ($user->has_permission(array(array("3" => array("write" => "1", "read" => "1"))), 0)) {
        ?>
        <a href='index.php?p=product_add'>Добавить</a>
        <?php
    }
    ?>
    <a href="index.php?p=price_tag">Ценники</a>
    <?php
}


function p_content() {
    ?> 
     <!-- Load Queue widget CSS and jQuery --> 
    <link rel="stylesheet" type="text/css" href="js/jquery.plupload.queue/css/jquery.plupload.queue.css">

    <!-- Load plupload and all it's runtimes and finally the jQuery queue widget --> 
    <script type="text/javascript" src="js/plupload.js"></script>
    <script type="text/javascript" src="js/jquery.plupload.queue/jquery.plupload.queue.js"></script> 

    <script type="text/javascript">        
                    
        // Convert divs to queue widgets when the DOM is ready  
        function plupload_init( ) { 
            // Setup html5 version 
            $("#html5_uploader").pluploadQueue({  
                // General settings  
                runtimes : 'html5',  
                url : 'product_add_multiple.php',  
                max_file_size : '10mb',  
                chunk_size : '1mb',  
                unique_names : true,
                filters : [{title : "Image files", extensions : "jpg"}],
                // Resize images on clientside if we can  
                resize : {width : 1024, height : 682, quality : 100}
            });              
        };  
    </script>  


    <div id="html5_uploader"></div>  

    <script type="text/javascript">
        plupload_init();
    </script>
    
    
    
    <?php
}
?>