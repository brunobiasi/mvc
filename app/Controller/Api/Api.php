<?php

namespace App\Controller\Api;

class Api {
    public static function getDetails($request) {
        return [
            'nome' => 'API - MVC',
            'versao' => 'v1.0.0',
            'autor' => 'Bruno Biasi',
            'email' => 'brunobiasi32@gmail.com'
        ];
    }

    protected static function getPagination($request, $obPagination) {
        $queryParams = $request->getQueryParams();

        $pages = $obPagination->getPages();

        return [
            'paginaAtual' => isset($queryParams['page']) ? (int)$queryParams['page'] : 1,
            'quantidadePaginas' => !empty($pages) ? count($pages) : 1
        ];
    }
}
