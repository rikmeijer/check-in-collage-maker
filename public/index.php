<?php

require dirname(__DIR__) . '/bootstrap.php';
$unsplash_client = Unsplash\HttpClient::init([
    'applicationId' => $_SERVER['UNSPLASH_KEY'],
    'secret' => $_SERVER['UNSPLASH_SECRET'],
    'callbackUrl' => $_SERVER['UNSPLASH_CALLBACK_URL'],
    'utmSource' => 'Check-in collage generator'
        ]);

$photos = Unsplash\Photo::random(['topics' => 'animals,food-drink,travel,architecture-interior,business-work', 'orientation' => 'landscape', 'count' => 30])->toArray();
$columns = 6;
?>
 <!DOCTYPE html>
<html>
<head>
<title>Check-in collage generator</title>
<style>
    body {
        background-color: #fff;
    }
    p {
        margin:0 auto;
        width: 1200px;
    }
    img {
        vertical-align:middle;
        width: 200px;
        height: 150px;
        object-fit: cover;
    }
</style>
</head>

<body>
<p><?php
foreach ($photos as $index => $photo) {
        if ($index % $columns === 0) {
            ?></p><p><?php
        }
        ?><img src="<?= htmlentities($photo["urls"]["thumb"]); ?>" /><?php
}
    ?></p>
</body>

</html><?php
