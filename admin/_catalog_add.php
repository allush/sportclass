<?php

function p_permission() {
    return array(array("2" => array("write" => "1", "read" => "1")));
}

function p_title() {
    return "Добавление каталога";
}

function p_description() {
    return "Добавление каталога";
}

function p_dark_block() {
    ?>
    <a href="index.php?p=catalog">Назад</a>
    <?php
}

function p_content() {

    if (isset($_GET['parent']))
        $parent = $_GET['parent'];
    else
        $parent = 0;
    ?>

    <form action="catalog_add.php" method="post" enctype="multipart/form-data" >

        <input type="hidden" name="parent" value="<?php echo $parent; ?>" />

        <p>Название</p>
        <input type="text" name="name" required="required" />

        <p>Описание</p>
        <textarea name="description" ></textarea>

        <p>Обложка</p>
        <input type="file" name="cover" accept="image/jpeg" />
        
        <p><b>Связанные рубрики. К товарам данного каталога будут подгружаться тематические статьи и видеоролики из указанных ниже рубрик.</b></p>
        <?php
        $result = mysql_query("SELECT * FROM `heading` ORDER BY `name` ASC");
        while ($row = mysql_fetch_array($result)) {
            echo "<p><input id='heading" . $row['id_heading'] . "' name='id_heading[]' type='checkbox' value='" . $row['id_heading'] . "' /> <label for='heading" . $row['id_heading'] . "'>" . $row['name'] . "</label></p>";
        }
        ?>
        
        <p></p>
        <input type="submit" value="Добавить"/>
    </form>
    <?php
}
?>
