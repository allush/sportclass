<?php

function p_permission() {
    return array(array("1" => array("write" => "1", "read" => "1")));
}
function p_title() {
    return "Безопасность :: Роли :: Добавление новой роли";
}

function p_description() {
    return "Безопасность :: Роли :: Добавление новой роли";
}

function p_dark_block() {
    ?>
    <a href="index.php?p=role">Назад</a>
    <?php
}

function p_content() {
    ?>
    <form action="role_add.php" method="post">

        <input type="hidden" name="referer" value="<?php echo $_SERVER['HTTP_REFERER']; ?>" />
        <p>Имя</p>
        <input type="text" name="name" required="yes"   />
        <table>
            <tr><td>Объект доступа</td><td>Чтение</td><td>Запись</td></tr>
            <?php
            $result = mysql_query("SELECT * FROM `permission`") or die(mysql_error());
            while ($row = mysql_fetch_array($result)) {
                echo "<tr>
                <td>" . $row['name'] . "</td>
                    <td><input type='checkbox'  name='read[]'  value='" . $row['id_permission'] . "' /></td>
                    <td><input type='checkbox'  name='write[]' value='" . $row['id_permission'] . "' /></td>
                    </tr>";
            }
            ?>

        </table>
        <p></p>
        <input type="submit" value="Добавить"/>
    </form>
    <?php
}
?>