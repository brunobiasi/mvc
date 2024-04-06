<?php

namespace App\Controller\Api;

use App\Db\Pagination;
use App\Model\Entity\Testimony as EntityTestimony;
use Exception;

class Testimony extends Api {
    public static function getTestimonyItems($request, &$obPagination) {
        $itens = [];

        $quantidadeTotal = EntityTestimony::getTestimonies(null, null, null, 'COUNT(*) as qtd')->fetchObject()->qtd;

        $queryParams = $request->getQueryParams();
        $paginaAtual = $queryParams['page'] ?? 1;

        $obPagination = new Pagination($quantidadeTotal, $paginaAtual, 5);

        $results = EntityTestimony::getTestimonies(null, 'id DESC', $obPagination->getLimit());

        while ($obTestimony = $results->fetchObject(EntityTestimony::class)) {
            $itens[] = [
                'id' => $obTestimony->id,
                'nome' => $obTestimony->nome,
                'mensagem' => $obTestimony->mensagem,
                'data' => $obTestimony->data,
            ];
        }
        return $itens;
    }

    public static function getTestimonies($request) {
        return [
            'depoimentos' => self::getTestimonyItems($request, $obPagination),
            'paginacao' => parent::getPagination($request, $obPagination),
        ];
    }

    public static function getTestimony($request, $id) {
        if (!is_numeric($id)) {
            throw new Exception("O id '" . $id . "' não é válido.", 400);
        }

        $obTestimony = EntityTestimony::getTestimonyById($id);

        if (!$obTestimony instanceof EntityTestimony) {
            throw new Exception("O depoimento " . $id . " não foi encontrado", 404);
        }

        return [
            'id' => $obTestimony->id,
            'nome' => $obTestimony->nome,
            'mensagem' => $obTestimony->mensagem,
            'data' => $obTestimony->data,
        ];
    }

    public static function setNewTestimony($request) {
        $postVars = $request->getPostVars();

        if (!isset($postVars['nome']) or !isset($postVars['mensagem'])) {
            throw new Exception("Os campos 'nome' e 'mensagem' são obrigatórios", 400);
        }

        $obTestimony = new EntityTestimony;
        $obTestimony->nome = $postVars['nome'];
        $obTestimony->mensagem = $postVars['mensagem'];
        $obTestimony->cadastrar();

        return [
            'id' => $obTestimony->id,
            'nome' => $obTestimony->nome,
            'mensagem' => $obTestimony->mensagem,
            'data' => $obTestimony->data,
        ];
    }

    public static function setEditTestimony($request, $id) {
        $postVars = $request->getPostVars();

        if (!isset($postVars['nome']) or !isset($postVars['mensagem'])) {
            throw new Exception("Os campos 'nome' e 'mensagem' são obrigatórios", 400);
        }

        $obTestimony = EntityTestimony::getTestimonyById($id);

        if (!$obTestimony instanceof EntityTestimony) {
            throw new Exception("O depoimento " . $id . " não foi encontrado", 404);
        }

        $obTestimony->nome = $postVars['nome'];
        $obTestimony->mensagem = $postVars['mensagem'];
        $obTestimony->atualizar();

        return [
            'id' => $obTestimony->id,
            'nome' => $obTestimony->nome,
            'mensagem' => $obTestimony->mensagem,
            'data' => $obTestimony->data,
        ];
    }

    public static function setDeleteTestimony($request, $id) {
        $obTestimony = EntityTestimony::getTestimonyById($id);

        if (!$obTestimony instanceof EntityTestimony) {
            throw new Exception("O depoimento " . $id . " não foi encontrado", 404);
        }

        $obTestimony->excluir();

        return [
            'sucesso' => true
        ];
    }
}
