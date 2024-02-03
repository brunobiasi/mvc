<?php

require __DIR__ . '/../vendor/autoload.php';

use App\Common\Environment;
use App\Db\Database;
use \App\Utils\View;

Environment::load(__DIR__ . '/../');

Database::config(
    getenv('DB_HOST'),
    getenv('DB_NAME'),
    getenv('DB_USER'),
    getenv('DB_PASS'),
    getenv('DB_PORT'),
);

define('URL', getenv('URL'));

View::init([
    'URL' => URL
]);
