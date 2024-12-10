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
    body > p, form, footer {
        margin:0 auto;
        width: 1200px;
        text-align: center;
    }
    footer {
        font-size: 0.8em;
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
    foreach ($photos(isset($_GET['renew']), $_POST['topic'] ?? ['animals', 'food-drink', 'travel', 'architecture-interior', 'business-work']) as $index => $photo) {
        if ($index % $columns === 0) {
            ?></p><p><?php
        }
        ?><img src="<?= htmlentities($photo["urls"]["thumb"]); ?>" onclick="this.src='index.php?random&' + (new Date()).toString()" /><?php
}
        ?></p>
<p><a href="index.php?renew">Random new set</a></p>
<p><form method="post">
    <p><select multiple="multiple" name="topic[]">
            <?php foreach (@Unsplash\Topic::all()->toArray() as $collection): ?>
            <option value="<?= htmlentities($collection['id']); ?>"><?= htmlentities($collection['title']); ?></option>
        <?php endforeach; ?>
        </select></p>
    <p><input type="submit" value="Random set with topic(s)"></p>
</form>
</p>
</body>
<footer><a href="https://www.paypal.com/donate/?hosted_button_id=B3WRGUUNZVDCL">Donate via PayPal</a></footer>
 </html><?php
