<?php

namespace App\Controller\Pages;

use App\Model\Entity\Testimony as EntityTestimony;
use \App\Utils\View;

class Testimony extends Page {

    public static function getTestimonies() {


        $content = View::render('pages/testimonies', []);

        return parent::getPage('DEPOIMENTOS > MVC', $content);
    }

    public static function insertTestimony($request) {
        $postVars = $request->getPostVars();

        $obTestimony = new EntityTestimony;
        $obTestimony->nome = $postVars['nome'];
        $obTestimony->mensagem = $postVars['mensagem'];
        $obTestimony->cadastrar();

        return self::getTestimonies();
    }
}
