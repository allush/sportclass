<?php
$id_product = $_POST['id_product'];
$num_products = $_POST['num_products'];

include_once '../common/connect.php';

function s_catalog($id = 0, $margin = 0) {
    $res = mysql_query("SELECT * FROM `catalog` WHERE `parent`='$id'");
    while ($row = mysql_fetch_array($res)) {
        $id_catalog = $row['id_catalog'];
        $name = $row['name'];
        ?>
        <div style='margin-left: <?php echo $margin . "px"; ?>'>
            <p>
                <input id="catalog<?php echo $id_catalog; ?>" name="id_catalog[]" type="checkbox" value="<?php echo $id_catalog; ?>" />
                <label for="catalog<?php echo $id_catalog; ?>"><?php echo $name; ?></label>
            </p>
        </div>

        <?php
        $margin +=16;
        s_catalog($id_catalog, $margin);
        $margin -=16;
    }
}
?>
<p style="text-align: right;">Чтобы закрыть окно нажмите <i>"Esc"</i></p>
<div style="margin: 0 auto; width: 400px;" >
    <p><b>Разместить выделенные товары (<?php echo $num_products; ?> шт.) в каталогах:</b></p><br>
    <form action="product_edit_options.php" method="post">
        <?php foreach ($id_product as $id) { ?>
            <input type="hidden" name="id_product[]" value="<?php echo $id ?>" />
            <?php
        }
        s_catalog();
        ?>
            <style type="text/css">
                table#options td{
                    text-align: center;
                    padding: 4px;
                }
            </style>
            
        <table id="options">
            <tr><td>Скидка, %</td><td><input min="0" name="discount" size="5" type="number" /></td></tr>
            <tr><td>Показывать</td><td> <input name="visible" type="checkbox"/> </td></tr>
        </table>
        <button>Применить</button>
    </form>
</div>