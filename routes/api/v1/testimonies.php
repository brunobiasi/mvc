<?php

use App\Controller\Api;
use App\Http\Response;

$obRouter->get('/api/v1/testimonies', [
    'middlewares' => [
        'api'
    ],
    function ($request) {
        return new Response(200, Api\Testimony::getTestimonies($request), 'application/json');
    }
]);

$obRouter->get('/api/v1/testimonies/{id}', [
    'middlewares' => [
        'api'
    ],
    function ($request, $id) {
        return new Response(200, Api\Testimony::getTestimony($request, $id), 'application/json');
    }
]);
