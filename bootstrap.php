<?php

require_once __DIR__ . '/vendor/autoload.php';

$dotenv_file = __DIR__ . '/.env';
is_file($dotenv_file) || touch($dotenv_file);
$dotenv = Dotenv\Dotenv::createMutable(dirname($dotenv_file));
$dotenv->load();

$cache_directory = __DIR__ . '/cache';
$max_images = 30;

return function () use ($cache_directory, $max_images) {
    Unsplash\HttpClient::init([
        'applicationId' => $_SERVER['UNSPLASH_KEY'],
        'secret' => $_SERVER['UNSPLASH_SECRET'],
        'callbackUrl' => $_SERVER['UNSPLASH_CALLBACK_URL'],
        'utmSource' => 'Check-in collage generator'
    ]);

    is_dir($cache_directory) || mkdir($cache_directory);

    $cached_files = glob($cache_directory . '/*.php');
    $yielded = 0;
    foreach (array_rand($cached_files, $max_images) as $cache_file_index) {
        yield include $cached_files[$cache_file_index];
        $yielded++;
    }

    if ($yielded >= $max_images) {
        return;
    }

    $photos = Unsplash\Photo::random(['topics' => 'animals,food-drink,travel,architecture-interior,business-work', 'orientation' => 'landscape', 'count' => $max_images - $yielded])->toArray();
    foreach ($photos as $photo) {
        file_put_contents($cache_directory . '/' . $photo['id'] . '.php', '<?php return ' . var_export($photo, true) . ';');
        yield $photo;
    }
};
