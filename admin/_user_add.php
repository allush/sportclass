<?php

function p_permission() {
    return array(array("1" => array("write" => "1", "read" => "1")));
}
function p_title() {
    return "Безопасность :: Пользователи :: Добавление нового пользователя";
}

function p_description() {
    return "Безопасность :: Пользователи :: Добавление нового пользователя";
}

function p_dark_block() {
    ?>
    <a href="index.php?p=user">Назад</a>
    <?php
}

function p_content() {
    ?>
    <form action="user_add.php" method="post">

        <input type="hidden" name="referer" value="<?php echo $_SERVER['HTTP_REFERER']; ?>" />
        <p>Имя</p>
        <input type="text" name="first_name" required="yes"   />

        <p>Фамилия</p>
        <input type="text" name="last_name"   required="yes"   />

        <p>email</p>
        <input type="email" name="email"   required="yes"   />

        <p>Телефон</p>
        <input type="tel" name="phone"   required="yes"   />

        <p>Пароль</p>
        <input type="password" name="password"   required="yes"   />

        <p>Роли</p>
        <select name="id_role[]" multiple="yes" size="4" required="yes">
            <?php
            $result = mysql_query("SELECT * FROM `role`") or die(mysql_error());
            while ($row = mysql_fetch_array($result)) {
                echo "<option value='" . $row['id_role'] . "'>" . $row['name'] . "</option>";
            }
            ?>
        </select>

        <p>Фото (горизонтальное фото с отношением сторон 3:2)</p>
        <input type="file" name="photo" accept="image/jpeg" />

        <p>Информация</p>
        <textarea name="info"></textarea>
        
        <p></p>
        <input type="submit" value="Добавить"/>
    </form>
    <?php
}
?>