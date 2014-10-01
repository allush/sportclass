<?php

function p_permission() {
    return array(array("1" => array("write" => "1", "read" => "1")));
}

function p_title() {
    return "Безопасность :: Пользователи :: Редактирование пользователя";
}

function p_description() {
    return "Безопасность :: Пользователи :: Редактирование пользователя";
}

function p_dark_block() {
    ?>
    <a href="index.php?p=user">Назад</a>
    <?php
}

function p_content() {

    if (!isset($_GET['id_user']))
        return;
    $id_user = $_GET['id_user'];

    $user = new CUser($id_user)
    ?>
    <form action="user_edit.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="id_user" value="<?php echo $id_user; ?>" />
        <input type="hidden" name="referer" value="<?php echo $_SERVER['HTTP_REFERER']; ?>" />


        <p>Имя</p>
        <input type="text" name="first_name" required="required" value="<?php echo $user->first_name(); ?>"  />

        <p>Фамилия</p>
        <input type="text" name="last_name"   required="required"    value="<?php echo $user->last_name(); ?>"/>

        <p>email</p>
        <input type="email" name="email"   required="required"   value="<?php echo $user->email(); ?>" />

        <p>Телефон</p>
        <input type="tel" name="phone"   required="required"   value="<?php echo $user->phone(); ?>" />

        <p>Роли</p>
        <select name="id_role[]" multiple="yes" size="4" required="required">
            <?php
            $result = mysql_query("SELECT * FROM `role`") or die(mysql_error());
            while ($row = mysql_fetch_array($result)) {
                echo "<option value='" . $row['id_role'] . "' ";
                if ($user->has_role($row['id_role']))
                    echo "selected='yes' ";
                echo ">" . $row['name'] . "</option>";
            }
            ?>
        </select>


        <p>Фото (горизонтальное фото с отношением сторон 3:2)</p>
        <p><img src="../img/master/<?php echo $user->photo(); ?>" /></p>
        <input type="file" name="photo" accept="image/jpeg" />

        <p>Информация</p>
        <textarea name="info"><?php echo $user->info(); ?></textarea>

        <p></p>
        <input type="submit" value="Редактировать"/>
    </form>
    <?php
}
?>