<?php

/**
 * 
 */
function p_permission() {
    return array(array("13" => array("write" => "0", "read" => "1")));
}

function p_title() {
    return "Редактирование статьи";
}

function p_description() {
    return "Редактирование статьи";
}

function p_dark_block() {
    ?>
    <a href="index.php?p=articles">Назад</a>
    <?php
}

function p_content() {
    ?>
    
    
    
    <!-- Load jQuery -->
    <script type="text/javascript" src="http://www.google.com/jsapi"></script>
    <script type="text/javascript">
        google.load("jquery", "1.3");
    </script>

    <!-- Load jQuery build -->
    <script type="text/javascript" src="js/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>
    <script type="text/javascript">
        tinyMCE.init({
            // General options
            mode : "textareas",
            theme : "advanced",
            plugins : "imagemanager,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template",

            // Theme options
            theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,styleselect,formatselect,fontselect,fontsizeselect",
            theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
            theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
            theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak,insertimage",
            theme_advanced_toolbar_location : "top",
            theme_advanced_toolbar_align : "left",
            theme_advanced_statusbar_location : "bottom",
            theme_advanced_resizing : true,

            // Example content CSS (should be your site CSS)
            content_css : "css/CArticle.css",

            // Drop lists for link/image/media/template dialogs
            template_external_list_url : "js/template_list.js",
            external_link_list_url : "js/link_list.js",
            external_image_list_url : "js/image_list.js",
            media_external_list_url : "js/media_list.js",

            // Replace values for the template plugin
            template_replace_values : {
                username : "Some User",
                staffid : "991234"
            }
        });
    </script>

    <?php
    include_once 'CArticle_be.php';
    
    if (!isset($_GET['id_article']) || !is_numeric($_GET['id_article']))
        return false;
    
    $id_article = (int) $_GET['id_article'];

    include_once '../common/CHeading.php';
    $article = new CArticle_be($id_article);
    ?>
    <form method="post" action="article_edit.php" enctype="multipart/form-data" >
        
        <input  type="hidden" name="id_article" value="<?php echo $id_article; ?>" />
        
        <p><b>Название</b></p>
        <input style="width: 774px;" type="text" name="title" value="<?php echo $article->title(); ?>"/>
        
        <p><b>Текст статьи</b></p>
        <textarea name="body"><?php echo $article->body(); ?></textarea>
        
        <p><b>Рубрика</b></p>
        <select name="id_heading"  style="width: 774px;" >
            <?php 
            $result = mysql_query("SELECT * FROM `heading`");
            while($row = mysql_fetch_array($result)){
                echo "<option ";
                if($row['id_heading'] == $article->idHeading())
                    echo "selected='selected'";
                echo " value='".$row['id_heading']."'>".$row['name']."</option>";
            }
            ?>
        </select>
        <p></p>
        <button>Сохранить</button>
    </form>




    <?php
}
?>
