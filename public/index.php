<?php
$photos = require dirname(__DIR__) . '/bootstrap.php';

if (isset($_GET['random'])) {
    $photos = iterator_to_array($photos(false));
    $photo = $photos[array_rand($photos, 1)];

    header('content-type: image/jpeg');
    print file_get_contents($photo["urls"]["thumb"]);
    exit;
}

$columns = 6;
?>
 <!DOCTYPE html>
<html>
<head>
<title>Check-in collage generator</title>
<style>
    body {
        background-color: #fff;
        font-family: monospace, sans-serif;
    }
    p {
        margin:0 auto;
        width: 1200px;
        text-align: center;
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
    foreach ($photos(isset($_GET['renew'])) as $index => $photo) {
        if ($index % $columns === 0) {
            ?></p><p><?php
        }
        ?><img src="<?= htmlentities($photo["urls"]["thumb"]); ?>" onclick="this.src='index.php?random&' + (new Date()).toString()" /><?php
}
        ?></p>
<p><a href="index.php?renew">New set</a></p>
</body>

</html><?php
