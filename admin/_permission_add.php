<?php

function p_permission() {
    return array(array("1" => array("write" => "1", "read" => "1")));
}
function p_title() {
    return "Добавление нового объекта доступа";
}

function p_description() {
    return "Добавление нового объекта доступа";
}

function p_dark_block() {
    ?>
    <a href="index.php?p=permission">Назад</a>
    <?php
}

function p_content() {
    ?>
    <form action="permission_add.php" method="post">

        <input type="hidden" name="referer" value="<?php echo $_SERVER['HTTP_REFERER']; ?>" />
        <p>Название</p>
        <input type="text" name="name" required="yes"   />

        <p></p>
        <input type="submit" value="Добавить"/>
    </form>
    <?php
}
?>