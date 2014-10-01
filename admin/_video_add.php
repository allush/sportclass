<?php

function p_permission() {
    return array(array("8" => array("write" => "1", "read" => "1")));
}

function p_title() {
    return "Добавление видео";
}

function p_description() {
    return "Добавление видео";
}

function p_dark_block() {
    ?>
    <a href="index.php?p=video">Назад</a>
    <?php
}

function p_content() {
    ?>
    <form action="video_add.php" method="post">

        <input type="hidden" name="referer" value="<?php echo $_SERVER['HTTP_REFERER']; ?>" />

        <p>Код видео на YouTube(11 символов)</p>
        <input type="text" placeholder="BvHHgc16Q3M" required="required" name="code"></textarea>

        <p>Рубрика</p>
        <select name="id_heading">
            <?php
            $result = mysql_query("SELECT * FROM `heading`");
            while ($row = mysql_fetch_array($result)) {
                echo "<option value='" . $row['id_heading'] . "'>" . $row['name'] . "</option>";
            }
            ?>
        </select>

        <p></p>
        <input type="submit" value="Добавить"/>
    </form>
    <?php
}
?>