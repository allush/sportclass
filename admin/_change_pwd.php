<?php

function p_permission() {
    return array(array("9" => array("write" => "0", "read" => "1")));
}

function p_title() {
    return "Мой профиль";
}

function p_description() {
    return "Мой профиль";
}

function p_dark_block() {
    if (!isset($_SESSION['id_user']) || !is_numeric($_SESSION['id_user']))
        return;

    $id_user = (int) $_SESSION['id_user'];
    $user = new CUser($id_user);
    if ($user->has_permission(array(array("9" => array("write" => "0", "read" => "1"))), 0)) {
        ?>
        <a href="index.php?p=my_profile">Мой профиль</a>
        <?php
    }

    if ($user->has_permission(array(array("10" => array("write" => "0", "read" => "1"))), 0)) {
        ?>
        <a href="index.php?p=my_product">Мои товары</a>
        <a href="index.php?p=my_cash">Моя касса (<?php
        echo $user->cash();
        ?> руб)
        </a>
        <?php
    }
}

function p_navigation() {
    ?>
    <a href="<?php echo $_SERVER['HTTP_REFERER']; ?>">Назад</a>    
    <?php
}

function p_content() {
    ?>
    <form action="change_pwd.php" method="post">
        <p>Текущий пароль</p>
        <input type="password" name="cur_password" required="required" />

        <p>Новый пароль</p>
        <input type="password" name="new_password" required="required" />

        <p></p>
        <button>Изменить</button>
    </form>
    <?php
}
?>