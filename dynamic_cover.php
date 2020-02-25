<?php

require_once '/var/www/html/projects/ivi_10years/src/Config.php';
require_once '/var/www/html/livecover/vendor/autoload.php';
require_once '/var/www/html/projects/ivi_10years/src/DB.php';

use MiniUpload\Config;
use Smit\Livecover\VK;

$vk = new VK(Config::$GROUP_ID, Config::$ACCESS_TOKEN);
$db = new \MiniUpload\DB();


$arr = $db->getFlag();
$result = $vk->getUsers($arr['user_id']);

$lines = array();
$text = $arr['comment'];
if (mb_strlen($text) < 110) {
    array_push($lines, $text);
}
else {
    while (mb_strlen($text) > 110) {
        strWorker($text);
    }
    array_push($lines, $text);
}

$first_name1 = $result[0]['first_name'];
$last_name1 = $result[0]['last_name'];
$photo1 =  new Imagick($result[0]['photo_200']);// Фотография
$post = new Imagick("/var/www/html/projects/ivi_10years/assets/images/cover.png"); // Фон обложки
$mask1 =  new Imagick("/var/www/html/projects/ivi_10years/assets/images/mask.png"); // Маска для скругления углов
$draw = new ImagickDraw();
$draw->setFillColor('rgb(255, 255, 255)'); // Цвет шрифта
$draw->setFontSize(28); // Размер шрифта
$draw->setFont("/var/www/html/projects/ivi_10years/assets/fonts/PSS55.otf"); // Выставляем шрифт
$draw->setTextAlignment(\Imagick::ALIGN_RIGHT); // Выравниваем текст по левому краю
$photo1->resizeImage(155, 155, Imagick::FILTER_LANCZOS, 1); // Настраиваем размеры
$mask1->resizeImage(155, 155, Imagick::FILTER_LANCZOS, 1);
$mask1->compositeImage($photo1, Imagick::COMPOSITE_ATOP, 0, 0); // Скругляем края фотографии
$photo1->clear();
$post->compositeImage($mask1, Imagick::COMPOSITE_OVER, 1235, 110); // Добавляем фото на обложку
$post->annotateImage($draw, 1200, 185, 0, $first_name1); // Добавляем текст на обложку
$post->annotateImage($draw, 1200, 210, 0, $last_name1); // Добавляем текст на обложку
$y = 285;
$draw->setFontSize(21); // Размер шрифта
$draw->setTextAlignment(\Imagick::ALIGN_LEFT); // Выравниваем текст по левому краю
foreach ($lines as $value) {
    $post->annotateImage($draw, 220, $y, 0, $value); // Добавляем текст на обложку
    $y += 23;
}
$post->writeImage('/var/www/html/projects/ivi_10years/tmp/upload_cover.png'); // Сохраняем изображение
$post->clear();
$vk->changeCover("/var/www/html/projects/ivi_10years/tmp/upload_cover.png");

function strWorker($str) {
    global $lines, $text;
    $str = substr($text, 0, 110);
    $num = strrpos($str, ' ');
    $str = substr($text, 0, $num + 1);
    $text = str_replace($str, '', $text);
    array_push($lines, $str);
}


