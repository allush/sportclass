<?php

/**
 * Функция создания миниатюр изображений
 * $path - путь к изображению
 * $w - ширина миниатюры
 */
function thumbnail($image, $path, $w) {
    if(!is_resource($image))
        return false;
    
    $h = round(($w / 3) * 2);

    $src_w = imagesx($image);
    $src_h = imagesy($image);
    
    if (($src_w != $w) || ($src_h != $h)) {

        $aspect = $w / $src_w;
        $t_w = $src_w * $aspect;
        $t_h = $src_h * $aspect;
        
        if($t_h != $h)
        {
            $aspect = $h / $t_h;
            $t_h *= $aspect;
            $t_w *= $aspect;
        }
        
        $h = round($t_h);
        $w = round($t_w);        
        
        $dst = imagecreatetruecolor($w, $h);
    }
// создание нового полноцветного изображения
// копирование начального изображения в новое пустое
    if ($dst)
        imagecopyresampled($dst, $image, 0, 0, 0, 0, $w, $h, $src_w, $src_h);
//в случае, если размер изображения меньше ограничений
    else
        $dst = $image;


    imagejpeg($dst, $path, 100);

    imagedestroy($dst); # Освобождение памяти
}

?>