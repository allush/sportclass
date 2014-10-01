<?php

function p_permission() {
    return array(array("1" => array("write" => "1", "read" => "1")));
}
function p_title() {
    return "Безопасность :: Роли :: Редактирование роли";
}

function p_description() {
    return "Безопасность :: Роли :: Редактирование роли";
}

function p_dark_block() {
    ?>
    <a href="index.php?p=role">Назад</a>
    <?php
}

function p_content() {
    if (!isset($_GET['id_role']))
        return;
    $id_role = $_GET['id_role'];

    $role = new CRole($id_role)
    ?>
    <form action="role_edit.php" method="post">

        <input type="hidden" name="id_role" value="<?php echo $role->id_role(); ?>" />
        <input type="hidden" name="referer" value="<?php echo $_SERVER['HTTP_REFERER']; ?>" />
        <p>Имя</p>
        <input type="text" name="name" required="yes" value="<?php echo $role->name(); ?>"  />

        <table>
            <tr><td>Объект доступа</td><td>Чтение</td><td>Запись</td></tr>
            <?php
            $result = mysql_query("SELECT * FROM `permission`") or die(mysql_error());
            while ($row = mysql_fetch_array($result)) {
                echo "<tr>";
                echo "<td>" . $row['name'] . "</td>";
               
                echo "<td><input type='checkbox'  name='read[]'  value='" . $row['id_permission'] . "' ";
                if($role->has_permission_read($row['id_permission']))
                    echo "checked=''";
                echo "/></td>";
                
                echo "<td><input type='checkbox'  name='write[]' value='" . $row['id_permission'] . "' ";
                if($role->has_permission_write($row['id_permission']))
                    echo "checked=''";
                echo "/></td>";
                
                echo "</tr>";
            }
            ?>

        </table>
        <p></p>
        <input type="submit" value="Редактировать"/>
    </form>
    <?php
}
?>