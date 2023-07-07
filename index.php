<?php

require __DIR__ . '/bootstrap/app.php';

use \App\Http\Router;
use \App\Utils\View;

define('URL', 'http://localhost/mvc');

View::init([
    'URL' => URL
]);

$obRouter = new Router(URL);

include __DIR__ . '/routes/pages.php';

$obRouter->run()->sendResponse();
