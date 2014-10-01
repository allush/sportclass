<?php

function p_permission() {
    return array(array("9" => array("write" => "0", "read" => "1")));
}

function p_title() {
    
    return "Мой профиль";
}

function p_description() {
    $id_user = (int) $_SESSION['id_user'];
    $user = new CUser($id_user);
    return $user->first_name()." ".$user->last_name();
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
    <a href="index.php?p=change_pwd">Изменить пароль</a>    
    <?php
}

function p_content() {
    if (!isset($_SESSION['id_user']) || !is_numeric($_SESSION['id_user']))
        return;

    $id_user = (int) $_SESSION['id_user'];
    $user = new CUser($id_user);
    ?>
    <div id="profile">
        <p id="photo"><img src="../img/master/<?php echo $user->photo(); ?>" /></p>
        <p><?php echo $user->first_name() . " " . $user->last_name(); ?></p>
        <p><?php echo $user->email(); ?> </p>
        <p><?php echo $user->phone(); ?> </p>
        <p><?php echo $user->info(); ?> </p>
    </div>
    <?php
}
?>