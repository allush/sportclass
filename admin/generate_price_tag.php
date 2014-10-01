<?php

session_start();
include '../common/CUser.php';
include '../common/connect.php';
include 'CPrice_tag.php';
include 'CProduct_be.php';
include 'wlog.php';
$user = new CUser($_SESSION['id_user']);
if (!$user->has_permission(array(array("3" => array("write" => "1", "read" => "1"))))) {
    if (isset($_GET['ajax'])) {
        die("Ошибка доступа");
    }
    header("Location: index.php?p=permission_denied");
    return;
}

if (!isset($_POST['type']))
    die("Не указан список для генерации ценников");

switch ($_POST['type']) {
    case "all":
        $result = mysql_query("SELECT * FROM `product`");
        break;
    case "selected":
        $result = mysql_query("SELECT * FROM `price_tag`");
        break;
}

$row = 0;
$col = 0;
$fn = array();
$zip = new ZipArchive();
$name = "price_tags/" . time() . ".zip";
$zip->open($name, ZIPARCHIVE::CREATE);

$new_sheet = true; //флаг создания нового листа
while ($row_price_tag = mysql_fetch_array($result)) {
    // Создаем новый лист, если кол-во строк = 0 или первый ценник
    if ($new_sheet) {
        $new_sheet = false;
        $sheet = imagecreatetruecolor(2480, 3508);
        imagefilledrectangle($sheet, 0, 0, 2480 - 1, 3508 - 1, imagecolorallocate($sheet, 255, 255, 255));
    }

    $price_tag = new CPrice_tag($row_price_tag['id_product']);
    $img = $price_tag->image();
    $src_w = imagesx($img);
    $src_h = imagesy($img);
    $dst_w = $src_w * $col + 2 * $col + 150;
    $dst_h = $src_h * $row + 2 * $row + 100;
    imagecopy($sheet, $img, $dst_w, $dst_h, 0, 0, $src_w, $src_h);
    imagedestroy($img);

    $col++;
    // если кол-во колонок достигло 3, то перейти на начало след строки
    if ($col == 3) {
        $col = 0;
        $row++;
    }

    // если кол-во строк достигло 7, то создание следующего листа, архивирование текущего и последующее его удаление
    if ($row == 7) {
        $new_sheet = true;
        $row = 0;
        $col = 0;

        //записываем в файл
        $fn[] = "price_tags/" . time() . ".png";
        imagepng($sheet, $fn[sizeof($fn) - 1]);
        // добавляем в архив
        $zip->addFile($fn[sizeof($fn) - 1]);
        // удаляем изображение
        imagedestroy($sheet);
        $sheet = 0;
    }

    $price_tag->delete();
}
//Если есть несохраненные ценники
if ($row || $col) {
    $fn[] = "price_tags/" . time() . ".png";
    //записываем в файл с именем fn
    imagepng($sheet, $fn[sizeof($fn) - 1]);
    // добавляем в архив
    $zip->addFile($fn[sizeof($fn) - 1]);
    // удаляем изображение
    imagedestroy($sheet);
    $sheet = 0;
}

// упаковываем картинки в архив 
$zip->close();

// Удаляем исходники
foreach ($fn as $f)
    unlink($f);

//
//header('Content-type: application/zip');
//header('Content-Disposition: attachment; filename="' . $name . '"');
//readfile($name);
if (file_exists($name)){
    $size = round(filesize($name)/1000000, 2);
    echo "<a href='" . $name . "'><img src='img/archive.png'/>Скачать архив ценников  (" . $size . " Mb)</a>";
}
else
    echo "Список ценников пуст";
?>
