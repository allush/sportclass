<?php

function p_title() {
    return "Ошибка доступа";
}

function p_description() {
    return "Ошибка доступа";
}

function p_dark_block() {
    ?>
    <a href="<?php echo $_SERVER['HTTP_REFERER']; ?>">Назад</a>
    <?php
}

function p_content() {
    ?>
    <div>У вас недостаточно прав на выполнение данной операции</div>
    <?php
}
?>