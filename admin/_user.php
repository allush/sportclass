<?php

function p_permission()
{
    return array(array("1" => array("write" => "0", "read" => "1")));
}

function p_title()
{
    return "Безопасность :: Пользователи";
}

function p_description()
{
    return "Безопасность :: Пользователи";
}

function p_dark_block()
{
    ?>
    <a href="index.php?p=user">Пользователи</a>
    <a href="index.php?p=role">Роли</a>
    <a href="index.php?p=permission">Объекты доступа</a>
    <a href="index.php?p=log">Логи</a>
<?php
}

function p_navigation()
{
    ?>
    <a href="index.php?p=user_add">Добавить нового пользователя</a>
<?php
}

function p_content()
{
    $result_role = mysql_query("SELECT * FROM `role`");
    while ($row_role = mysql_fetch_array($result_role)) {
        echo "<div style='padding: 4px; border-bottom: 1px solid #aaa; background-color: #f9f9f9;'>" . $row_role['name'] . "</div>";
        $result = mysql_query("SELECT * FROM `user` WHERE `id_user` IN (SELECT `id_user` FROM `user_role` WHERE `id_role`='" . $row_role['id_role'] . "') ORDER BY `last_name` ASC") or die(mysql_error());
        $i = 0;
        while ($row = mysql_fetch_array($result)) {
            $id_user = $row['id_user'];
            $user = new CUser($id_user);
            ?>
            <div class="list_item">
                <?php
                echo (++$i) . '. ';
                if ($user->deleted()) {
                    ?>
                    <a style="background-color: #fbc6c6;" href="index.php?p=user_edit&id_user=<?php echo $id_user; ?>">
                        <?php echo $user->last_name() . ' ' . $user->first_name(); ?>
                    </a>
                    <a class="small_grey right" href="user_undelete.php?id_user=<?php echo $id_user; ?>">
                        Восстановить
                    </a>
                <?php } else { ?>
                    <a title="<?php echo $user->email(); ?>"
                       href="index.php?p=user_edit&id_user=<?php echo $id_user; ?>">
                        <?php echo $user->last_name() . " " . $user->first_name(); ?>
                    </a>
                    <a class="small_grey right" href="user_delete.php?id_user=<?php echo $id_user; ?>"
                       onclick="if(!confirm('Вы действительно хотите удалить пользователя ( <?php echo $user->last_name() . " " . $user->first_name(); ?> )? ')) return false;">
                        Удалить
                    </a>
                <?php } ?>
                <div class="clearer"></div>
            </div>
        <?php
        }
        if (!$i)
            echo "Нет пользователей";
    }
}

?>