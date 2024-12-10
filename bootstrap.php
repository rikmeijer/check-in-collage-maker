<?php

require_once __DIR__ . '/vendor/autoload.php';

$dotenv_file = __DIR__ . '/.env';
is_file($dotenv_file) || touch($dotenv_file);
$dotenv = Dotenv\Dotenv::createMutable(dirname($dotenv_file));
$dotenv->load();

Unsplash\HttpClient::init([
    'applicationId' => $_SERVER['UNSPLASH_KEY'],
    'secret' => $_SERVER['UNSPLASH_SECRET'],
    'callbackUrl' => $_SERVER['UNSPLASH_CALLBACK_URL'],
    'utmSource' => 'Check-in collage generator'
]);

$cache_directory = __DIR__ . '/cache';
$max_images = 30;

return function (bool $renew, array $topics = ['animals', 'food-drink', 'travel', 'architecture-interior', 'business-work']) use ($cache_directory, $max_images) {
    is_dir($cache_directory) || mkdir($cache_directory);
    
    $yielded = 0;
    $cached_files = glob($cache_directory . '/*.php');
    if ($renew === false && count($cached_files) > 0) {
        foreach (array_rand($cached_files, min($max_images, count($cached_files))) as $cache_file_index) {
            yield include $cached_files[$cache_file_index];
            $yielded++;
        }
        if ($yielded >= $max_images) {
            return;
        }
    }

    $response = Unsplash\Photo::get("photos/random", ['query' => ['topics' => implode(',', $topics), 'orientation' => 'landscape', 'count' => $max_images - $yielded]]);
    $photos = json_decode($response->getBody(), true);
    foreach ($photos as $photo) {
        file_put_contents($cache_directory . '/' . $photo['id'] . '.php', '<?php return ' . var_export($photo, true) . ';');
        yield $photo;
    }
};
