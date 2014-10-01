<?php

function p_permission() {
    return array(array("2" => array("write" => "1", "read" => "1")));
}

function p_title() {
    return "Редактирование каталога";
}

function p_description() {
    return "Редактирование каталога";
}

function p_dark_block() {
    ?>
    <a href="index.php?p=catalog">Назад</a>
    <?php
}

function p_content() {    
    include_once '../common/CHeading.php';
    $id_catalog = $_GET['id'];
    settype($id_catalog, "int");

    $catalog = new CCatalog($id_catalog);
    ?>
    <form action="catalog_edit.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="id_catalog" value="<?php echo $id_catalog; ?>" />

        <p><b>Название</b></p>
        <input type="text" name="name" required="yes" value="<?php echo $catalog->name(); ?>" />
        <p><b>Описание</b></p>
        <textarea name="description"><?php echo $catalog->description(); ?></textarea>

        <p><b>Обложка</b></p>
        <p><img src="../img/catalog/<?php echo $catalog->cover(); ?>" /></p>
        <input type="file" name="cover" accept="image/jpeg" />

        <p><b>Родительский каталог</b></p>
        <select name="parent" >
            <option value="0">Верхний уровень</option>
            <?php
            $result_pc = mysql_query("SELECT * FROM `catalog`");
            if (!$result_pc)
                return;
            while ($row_pc = mysql_fetch_array($result_pc)) {
                if ($row_pc['id_catalog'] == $id_catalog)
                    continue;

                echo "<option ";
                if ($row_pc['id_catalog'] == $catalog->parent())
                    echo " selected='yes' ";
                echo " value='" . $row_pc['id_catalog'] . "'>" . $row_pc['name'] . "</option>";
            }
            ?>
        </select>
        <p><b>Связанные рубрики. К товарам данного каталога будут подгружаться тематические статьи и видеоролики из указанных ниже рубрик.</b></p>
        <?php
        $result = mysql_query("SELECT * FROM `heading` ORDER BY `name` ASC");
        while ($row = mysql_fetch_array($result)) {
            echo "<p><input ";
            if ($catalog->hasHeading(new CHeading($row['id_heading'])))
                echo " checked='checked' ";
            echo " id='heading" . $row['id_heading'] . "' name='id_heading[]' type='checkbox' value='" . $row['id_heading'] . "' /> <label for='heading" . $row['id_heading'] . "'>" . $row['name'] . "</label></p>";
        }
        ?>

        <p></p>
        <input type="submit" value="Изменить"/>
    </form>

    <?php
}
?>