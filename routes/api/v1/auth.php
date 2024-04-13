<?php

use App\Controller\Api;
use App\Http\Response;

$obRouter->post('/api/v1/auth', [
    'middlewares' => [
        'api'
    ],
    function ($request) {
        return new Response(201, Api\Auth::generateToken($request), 'application/json');
    }
]);
