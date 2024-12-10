<?php
$unsplash_factory = require dirname(__DIR__) . '/bootstrap.php';
$unsplash = $unsplash_factory(
        skip_cache: isset($_GET['renew']) || isset($_POST['topic']),
        topics: $_POST['topic'] ?? ['animals', 'food-drink', 'travel', 'architecture-interior', 'business-work']
);

if (isset($_GET['random'])) {
    $unsplash = iterator_to_array($unsplash(false));
    $photo = $unsplash[array_rand($unsplash, 1)];

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
    select {
        width: 400px;
        height: 200px;
    }
</style>
</head>

<body>
<p><?php
    foreach ($unsplash() as $index => $photo) {
        if ($index % $columns === 0) {
            ?></p><p><?php
        }
        ?><img src="<?= htmlentities($photo["urls"]["thumb"]); ?>" onclick="this.src='index.php?random&' + (new Date()).toString()" /><?php
}
        ?></p>
<p><a href="index.php">Random cached set</a>&nbsp;&bull;&nbsp;<a href="index.php?renew">Random new set</a></p>
<p><form method="post">
    <p><label for="topic_featured">Topics</label><br /><select multiple="multiple" id="topic_featured" name="topic[]">
            <?php foreach ($unsplash->available_topics() as $id => $topic): ?>
            <option value="<?= htmlentities($id); ?>"<?= ($topic['selected'] ? ' selected' : ''); ?>><?= htmlentities($topic['title']); ?></option>
            <?php endforeach; ?>
        </select></p>
        <p><input type="submit" value="Random set with topic(s)" /><input type="reset" value="Clear" /></p>
</form>
</p>
</body>
<footer><a href="https://www.paypal.com/donate/?hosted_button_id=B3WRGUUNZVDCL">Donate via PayPal</a></footer>
 </html><?php
