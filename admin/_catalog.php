<?php

/**
 * Установка необходимых требований к пользователю
 * он должен иметь право id_permission на write или read
 */
function p_permission() {
    return array(array("2" => array("write" => "0", "read" => "1")));
}

function p_title() {
    return "Каталоги";
}

function p_description() {
    return "Каталоги";
}

function p_dark_block() {
    ?>
    <a href='index.php?p=catalog_add'>Добавить каталог верхнего уровня</a>
    <?php
}

function p_content() {

    function catalogs($id = 0, $margin = 0) {
        $res = mysql_query("SELECT * FROM `catalog` WHERE `parent`='$id'");
        $num = mysql_num_rows($res);
        if ($num > 0) {
            for ($i = 0; $i < $num; $i++) {
                $row = mysql_fetch_array($res);
                $id_catalog = $row['id_catalog'];
                $name = $row['name'];
                ?>
                <div class="list_item" style='margin-left: <?php echo $margin . "px"; ?>'>
                    <?php echo ($i + 1); ?>. <a href='index.php?p=catalog_edit&id=<?php echo $id_catalog; ?>'> <?php echo $name; ?></a> 
                    <a class='small_grey right' href="catalog_delete.php?id=<?php echo $id_catalog; ?>" onclick="if(!confirm('Вы действительно хотите удалить каталог ( <?php echo $row['name']; ?> )? Все находяшиейся в нем каталоги будут удалены, а товары останутся вне каталогов!')) return false;">Удалить</a>
                    <a class='small_grey right' href='index.php?p=catalog_add&parent=<?php echo $id_catalog; ?>'>Добавить каталог-потомок</a>
                    <div class="clearer"></div>
                </div>

                <?php
                $margin +=16;
                catalogs($id_catalog, $margin);
                $margin -=16;
            }
        }
    }
    ?>
    <table>
        <?php catalogs(); ?>
    </table>
    <?php
}
?>
