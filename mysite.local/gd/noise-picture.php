<?php
// Проверяем, установлено ли расширение GD
if (!extension_loaded('gd')) {
    die('GD extension not installed');
}

// Загружаем исходное изображение
$sourceImage = imagecreatefromjpeg(__DIR__.'/images/noise.jpg');
if ($sourceImage === false) {
    die('Failed to load the source image.');
}

// Создаем новое изображение с заданными размерами
$newImage = imagecreatetruecolor(300, 100);

// Копируем исходное изображение на новое с изменением размера
imagecopyresampled($newImage, $sourceImage, 0, 0, 0, 0, 300, 100, imagesx($sourceImage), imagesy($sourceImage));

// Выбираем цвет для текста
$color = imagecolorallocate($newImage, 0, 0, 0);

// Включаем сглаживание текста
imageantialias($newImage, true);

// Генерируем случайный текст для капчи
$randomText = substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789"), 0, 6);

// Получаем список файлов шрифтов
$fontDirectory = __DIR__.'/fonts';
$fonts = scandir($fontDirectory);
$fonts = array_diff($fonts, array('.', '..'));

// Выбираем случайный шрифт из списка
$randomFont = $fonts[array_rand($fonts)];


// Рисуем каждую букву текста на изображении
$offsetX = 50;
$result = [];
foreach (str_split($randomText) as $letter) {
    $fontFile = $fontDirectory . '/' . $randomFont;
    // Генерируем случайный размер шрифта
    $fontSize = mt_rand(18, 30);
    // Рисуем каждую букву текста на изображении
    $textAngle = mt_rand(-30, 30);
    imagettftext($newImage, $fontSize, $textAngle, $offsetX, 60, $color, $fontFile, $letter);
    $result[] = $letter;
    $offsetX += $fontSize + 10;
    
}
session_start();
$_SESSION['captcha'] = $result;
// Отправляем заголовок, чтобы браузер распознал изображение
header('Content-type: image/jpeg');

// Отправляем изображение в браузер в формате JPEG
imagejpeg($newImage);

// Освобождаем память, удаляя временное изображение
imagedestroy($newImage);
imagedestroy($sourceImage);
exit;
