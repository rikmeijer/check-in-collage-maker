<?php

$unsplash = (require dirname(__DIR__) . '/bootstrap.php')();
$photos = iterator_to_array($unsplash());
$photo = $photos[array_rand($photos, 1)];

header('content-type: image/jpeg');
print file_get_contents($photo["urls"]["thumb"]);
exit;
