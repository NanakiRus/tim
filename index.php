<?php
//Пример использования
include __DIR__ . '/autoload.php';

$path = __DIR__ . '/upload';

$thumbPath = __DIR__ . '/upload/thumb';

$thumb = new \app\ImageThumb($path, $thumbPath);

$thumb->createFiles()->getThumb();
