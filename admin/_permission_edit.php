<?php

function p_permission() {
    return array(array("1" => array("write" => "1", "read" => "1")));
}
function p_title() {
    return "Редактирование объекта доступа";
}

function p_description() {
    return "Редактирование объекта доступа";
}

function p_dark_block() {
    ?>
    <a href="index.php?p=permission">Назад</a>
    <?php
}

function p_content() {
    if (!isset($_GET['id_permission']))
        return;
    $id_permission = $_GET['id_permission'];

    $result = mysql_query("SELECT * FROM `permission` WHERE `id_permission`='$id_permission'") or die(mysql_error());
    $row = mysql_fetch_array($result);
    ?>
    <form action="permission_edit.php" method="post">
        
        <input type="hidden" name="id_permission" value="<?php echo $id_permission; ?>" />
        <input type="hidden" name="referer" value="<?php echo $_SERVER['HTTP_REFERER']; ?>" />
        <p>Название</p>
        <input type="text" name="name" required="yes"  value="<?php echo $row['name']; ?>"  />

        <p></p>
        <input type="submit" value="Редактировать"/>
    </form>
    <?php
}
?>