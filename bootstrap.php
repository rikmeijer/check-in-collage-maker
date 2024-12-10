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

$defaults = [
    'cache_directory' => __DIR__ . '/cache',
    'max_images' => 30
];

return fn(...$args): rikmeijer\CheckInCollageMaker\Unsplash => new rikmeijer\CheckInCollageMaker\Unsplash(...array_merge($defaults, $args));
