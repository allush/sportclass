<?php

function p_permission() {
    return array(array("1" => array("write" => "0", "read" => "1")));
}

function p_title() {
    return "Безопасность :: Объекты доступа";
}

function p_description() {
    return "Безопасность :: Объекты доступа";
}

function p_dark_block() {
    ?>
    <a href="index.php?p=user">Пользователи</a>
    <a href="index.php?p=role">Роли</a>
    <a href="index.php?p=permission">Объекты доступа</a>
    <a href="index.php?p=log">Логи</a>
    <?php
}

function p_navigation() {
    ?>
    <a href="index.php?p=permission_add">Добавить новый объект доступа</a>
    <?php
}

function p_content() {
    $result = mysql_query("SELECT * FROM `permission`") or die(mysql_error());
    $i = 0;
    while ($row = mysql_fetch_array($result)) {
        $id_permission = $row['id_permission'];
        $name = $row['name'];
        ?>
        <div class="list_item">
            <?php echo++$i; ?>. <a href='index.php?p=permission_edit&id_permission=<?php echo $id_permission; ?>'> <?php echo $name; ?></a> 
            <a class='small_grey right' href="permission_delete.php?id_permission=<?php echo $id_permission; ?>" onclick="if(!confirm('Вы действительно хотите удалить объект доступа ( <?php echo $row['name']; ?> )?')) return false;">Удалить</a>
            <div class="clearer"></div>
        </div>
        <?php
    }
    if (!$i)
        echo "Нет объектов доступа";
}
?>