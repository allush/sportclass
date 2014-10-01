<?php

function p_permission() {
    return array(array("14" => array("write" => "0", "read" => "1")));
}

function p_title() {
    return "Редактирование рубрики";
}

function p_description() {
    return "Редактирование рубрики";
}

function p_dark_block() {
    ?>
    <a href="index.php?p=heading">Назад</a>
    <?php
}

function p_content() {
    if (!isset($_GET['id_heading']) || !is_numeric($_GET['id_heading']))
        return false;

    $id_heading = (int) $_GET['id_heading'];

    include_once '../common/CHeading.php';
    $heading = new CHeading($id_heading);
    ?>


    <form method="post" action="heading_edit.php">
        <input type="hidden" name="id_heading" value="<?php echo $id_heading; ?>" />

        <p>Название</p>
        <input type="text" name="name" value="<?php echo $heading->name(); ?>" />

        <p>Показывать на главной странице</p>
        <input type="checkbox" name="onMain" <?php if ($heading->onMain()) echo "checked"; ?> />

        <p></p>
        <button>Редактировать</button>
    </form>




    <?php
}
?>
