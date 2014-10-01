<?php

function p_permission() {
    return array(array("1" => array("write" => "0", "read" => "1")));
}

function p_title() {
    return "Безопасность :: Роли";
}

function p_description() {
    return "Безопасность :: Роли";
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
    <a href="index.php?p=role_add">Добавить новую роль</a>
    <?php
}

function p_content() {
    $result = mysql_query("SELECT * FROM `role`") or die(mysql_error());
    $i = 0;
    while ($row = mysql_fetch_array($result)) {
        $id_role = $row['id_role'];
        $name = $row['name'];
        ?>
        <div class="list_item">
            <?php echo++$i; ?>. <a href='index.php?p=role_edit&id_role=<?php echo $id_role; ?>'> <?php echo $name; ?></a> 
            <a class='small_grey right' href="role_delete.php?id_role=<?php echo $id_role; ?>" onclick="if(!confirm('Вы действительно хотите удалить роль ( <?php echo $name; ?> )? Все пользователи этой роли будут также удалены!')) return false;">Удалить</a>
            <div class="clearer"></div>
        </div>
        <?php
    }
    if(!$i)
        echo "Нет ролей";
}
?>