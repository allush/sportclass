<?php

function logo($image) {
    $w = imagesx($image);
    $h = imagesy($image);
    
    $logo_name = "img/wm.png";
    $logo_img = @imagecreatefrompng($logo_name);
    if ($logo_img) {
//Высота исходного изоборажения логотипа
        $h_logo_src = imagesy($logo_img);

//Ищем гипотенузу
        $c = 0;
        $c = sqrt(pow($w, 2) + pow($h, 2));

//Ищем угол наклона гипотенузы
        $rad_angle = asin($h / $c);
        $deg_angle = rad2deg($rad_angle);

//Поворачиваем логотип    
        $logo_img = imagerotate($logo_img, $deg_angle, -1);

//Высота логотипа после поворота
        $w_logo = imagesx($logo_img);
        $h_logo = imagesy($logo_img);

//Кол-во логотипов для данного изображения
        $num_logo = $c / $w_logo;

//Добавочная высота и ширина
        $d_h = $h_logo_src * abs(sin(deg2rad(90 - $deg_angle)));
        $d_w = -$h_logo_src * abs(cos(deg2rad(90 - $deg_angle)));

        for ($i = 0; $i < $num_logo + 1; $i++) {
            imagecopy($image, $logo_img, $i * $w_logo + ($i + 1) * $d_w - $d_w / 2, $h - ($i + 1) * $h_logo + ($i + 1) * $d_h - $d_h / 2, 0, 0, $w_logo, $h_logo);
        }
    }
}

?>
