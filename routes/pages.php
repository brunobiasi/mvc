<?php

use \App\Http\Response;
use \App\Controller\Pages;

$obRouter->get('/', [
    function () {
        return new Response(200, Pages\Home::getHome());
    }
]);

$obRouter->get('/sobre', [
    function () {
        return new Response(200, Pages\About::getAbout());
    }
]);

$obRouter->get('/depoimentos', [
    function () {
        return new Response(200, Pages\Testimony::getTestimonies());
    }
]);
$obRouter->post('/depoimentos', [
    function ($request) {
        return new Response(200, Pages\Testimony::insertTestimony($request));
    }
]);
