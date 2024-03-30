<?php

namespace App\Controller\Admin;

use App\Db\Pagination;
use App\Utils\View;
use App\Model\Entity\Testimony as EntityTestimony;

class Testimony extends Page {
    public static function getTestimonyItems($request, &$obPagination) {
        $itens = '';

        $quantidadeTotal = EntityTestimony::getTestimonies(null, null, null, 'COUNT(*) as qtd')->fetchObject()->qtd;

        $queryParams = $request->getQueryParams();
        $paginaAtual = $queryParams['page'] ?? 1;

        $obPagination = new Pagination($quantidadeTotal, $paginaAtual, 5);

        $results = EntityTestimony::getTestimonies(null, 'id DESC', $obPagination->getLimit());

        while ($obTestimony = $results->fetchObject(EntityTestimony::class)) {
            $itens .= View::render('admin/modules/testimonies/item', [
                'id' => $obTestimony->id,
                'nome' => $obTestimony->nome,
                'mensagem' => $obTestimony->mensagem,
                'data' => date('d/m/Y H:i:s', strtotime($obTestimony->data))
            ]);
        }
        return $itens;
    }

    public static function getTestimonies($request) {
        $content = View::render('admin/modules/testimonies/index', [
            'itens' => self::getTestimonyItems($request, $obPagination),
            'pagination' => parent::getPagination($request, $obPagination),
            'status' => self::getStatus($request),
        ]);

        return parent::getPanel('Depoimentos > MVC', $content, 'testimonies');
    }

    public static function getNewTestimony($request) {
        $content = View::render('admin/modules/testimonies/form', [
            'title' => 'Cadastrar depoimento',
            'nome' => '',
            'mensagem' => '',
            'status' => '',
        ]);

        return parent::getPanel('Cadastrar depoimento > MVC', $content, 'testimonies');
    }

    public static function setNewTestimony($request) {
        $postVars = $request->getPostVars();

        $obTestimony = new EntityTestimony;
        $obTestimony->nome = $postVars['nome'] ?? '';
        $obTestimony->mensagem = $postVars['mensagem'] ?? '';
        $obTestimony->cadastrar();

        $request->getRouter()->redirect('/admin/testimonies/' . $obTestimony->id . '/edit?status=created');
    }

    public static function getStatus($request) {
        $queryParams = $request->getQueryParams();

        if (!isset($queryParams['status'])) return '';

        switch ($queryParams['status']) {
            case 'created':
                return Alert::getSuccess('Depoimento criado com sucesso!');
                break;
            case 'updated':
                return Alert::getSuccess('Depoimento atualizado com sucesso!');
                break;
            case 'deleted':
                return Alert::getSuccess('Depoimento excluído com sucesso!');
                break;
        }
    }

    public static function getEditTestimony($request, $id) {
        $obTestimony = EntityTestimony::getTestimonyById($id);

        if (!$obTestimony instanceof EntityTestimony) {
            $request->getRouter()->redirect('/admin/testimonies');
        }

        $content = View::render('admin/modules/testimonies/form', [
            'title' => 'Editar depoimento',
            'nome' => $obTestimony->nome,
            'mensagem' => $obTestimony->mensagem,
            'status' => self::getStatus($request),
        ]);

        return parent::getPanel('Editar depoimento > MVC', $content, 'testimonies');
    }

    public static function setEditTestimony($request, $id) {
        $obTestimony = EntityTestimony::getTestimonyById($id);

        if (!$obTestimony instanceof EntityTestimony) {
            $request->getRouter()->redirect('/admin/testimonies');
        }

        $postVars = $request->getPostVars();

        $obTestimony->nome = $postVars['nome'] ?? $obTestimony->nome;
        $obTestimony->mensagem = $postVars['mensagem'] ?? $obTestimony->mensagem;
        $obTestimony->atualizar();

        $request->getRouter()->redirect('/admin/testimonies/' . $obTestimony->id . '/edit?status=updated');
    }

    public static function getDeleteTestimony($request, $id) {
        $obTestimony = EntityTestimony::getTestimonyById($id);

        if (!$obTestimony instanceof EntityTestimony) {
            $request->getRouter()->redirect('/admin/testimonies');
        }

        $content = View::render('admin/modules/testimonies/delete', [
            'nome' => $obTestimony->nome,
            'mensagem' => $obTestimony->mensagem,
        ]);

        return parent::getPanel('Excluir depoimento > MVC', $content, 'testimonies');
    }

    public static function setDeleteTestimony($request, $id) {
        $obTestimony = EntityTestimony::getTestimonyById($id);

        if (!$obTestimony instanceof EntityTestimony) {
            $request->getRouter()->redirect('/admin/testimonies');
        }

        $obTestimony->excluir();

        $request->getRouter()->redirect('/admin/testimonies?status=deleted');
    }
}
