<?php

use \App\Http\Response;
use \App\Controller\Pages;

$obRouter->get('/', [
    'middlewares' => [
        'cache'
    ],
    function () {
        return new Response(200, Pages\Home::getHome());
    }
]);

$obRouter->get('/sobre', [
    'middlewares' => [
        'cache'
    ],
    function () {
        return new Response(200, Pages\About::getAbout());
    }
]);

$obRouter->get('/depoimentos', [
    'middlewares' => [
        'cache'
    ],
    function ($request) {
        return new Response(200, Pages\Testimony::getTestimonies($request));
    }
]);
$obRouter->post('/depoimentos', [
    function ($request) {
        return new Response(200, Pages\Testimony::insertTestimony($request));
    }
]);
