<?php

require __DIR__ . '/includes/app.php';

use \App\Http\Router;

$obRouter = new Router(URL);

include __DIR__ . '/routes/pages.php';
include __DIR__ . '/routes/admin.php';
include __DIR__ . '/routes/api.php';

$obRouter->run()->sendResponse();
