<?php

require __DIR__ . '/bootstrap/app.php';

use \App\Http\Router;

$obRouter = new Router(URL);

include __DIR__ . '/routes/pages.php';

$obRouter->run()->sendResponse();
