<?php

namespace App\Controller\Admin;

use App\Db\Pagination;
use App\Utils\View;
use App\Model\Entity\User as EntityUser;

class User extends Page {
    public static function getUserItems($request, &$obPagination) {
        $itens = '';

        $quantidadeTotal = EntityUser::getUsers(null, null, null, 'COUNT(*) as qtd')->fetchObject()->qtd;

        $queryParams = $request->getQueryParams();
        $paginaAtual = $queryParams['page'] ?? 1;

        $obPagination = new Pagination($quantidadeTotal, $paginaAtual, 5);

        $results = EntityUser::getUsers(null, 'id DESC', $obPagination->getLimit());

        while ($obUser = $results->fetchObject(EntityUser::class)) {
            $itens .= View::render('admin/modules/users/item', [
                'id' => $obUser->id,
                'nome' => $obUser->nome,
                'email' => $obUser->email,
            ]);
        }
        return $itens;
    }

    public static function getUsers($request) {
        $content = View::render('admin/modules/users/index', [
            'itens' => self::getUserItems($request, $obPagination),
            'pagination' => parent::getPagination($request, $obPagination),
            'status' => self::getStatus($request),
        ]);

        return parent::getPanel('Usuários > MVC', $content, 'users');
    }

    public static function getNewUser($request) {
        $content = View::render('admin/modules/users/form', [
            'title' => 'Cadastrar usuário',
            'nome' => '',
            'email' => '',
            'status' => self::getStatus($request),
        ]);

        return parent::getPanel('Cadastrar usuário > MVC', $content, 'users');
    }

    public static function setNewUser($request) {
        $postVars = $request->getPostVars();
        $nome = $postVars['nome'] ?? '';
        $email = $postVars['email'] ?? '';
        $senha = $postVars['senha'] ?? '';

        $obUser = EntityUser::getUserByEmail($email);

        if ($obUser instanceof EntityUser) {
            $request->getRouter()->redirect('/admin/users/new?status=duplicated');
        }

        $obUser = new EntityUser;
        $obUser->nome = $nome;
        $obUser->email = $email;
        $obUser->senha = password_hash($senha, PASSWORD_DEFAULT);
        $obUser->cadastrar();

        $request->getRouter()->redirect('/admin/users/' . $obUser->id . '/edit?status=created');
    }

    public static function getStatus($request) {
        $queryParams = $request->getQueryParams();

        if (!isset($queryParams['status'])) return '';

        switch ($queryParams['status']) {
            case 'created':
                return Alert::getSuccess('Usuário criado com sucesso!');
                break;
            case 'updated':
                return Alert::getSuccess('Usuário atualizado com sucesso!');
                break;
            case 'deleted':
                return Alert::getSuccess('Usuário excluído com sucesso!');
                break;
            case 'duplicated':
                return Alert::getError('O e-mail digitado já está sendo utilizado por outro usuário.');
                break;
        }
    }

    public static function getEditUser($request, $id) {
        $obUser = EntityUser::getUserById($id);

        if (!$obUser instanceof EntityUser) {
            $request->getRouter()->redirect('/admin/users');
        }

        $content = View::render('admin/modules/users/form', [
            'title' => 'Editar usuário',
            'nome' => $obUser->nome,
            'email' => $obUser->email,
            'status' => self::getStatus($request),
        ]);

        return parent::getPanel('Editar usuário > MVC', $content, 'users');
    }

    public static function setEditUser($request, $id) {
        $obUser = EntityUser::getUserById($id);

        if (!$obUser instanceof EntityUser) {
            $request->getRouter()->redirect('/admin/users');
        }

        $postVars = $request->getPostVars();
        $nome = $postVars['nome'] ?? '';
        $email = $postVars['email'] ?? '';
        $senha = $postVars['senha'] ?? '';

        $obUserEmail = EntityUser::getUserByEmail($email);

        if ($obUserEmail instanceof EntityUser && $obUserEmail->id != $id) {
            $request->getRouter()->redirect('/admin/users/' . $id . '/edit?status=duplicated');
        }

        $obUser->nome = $nome;
        $obUser->email = $email;
        $obUser->senha = password_hash($senha, PASSWORD_DEFAULT);
        $obUser->atualizar();

        $request->getRouter()->redirect('/admin/users/' . $obUser->id . '/edit?status=updated');
    }

    public static function getDeleteUser($request, $id) {
        $obUser = EntityUser::getUserById($id);

        if (!$obUser instanceof EntityUser) {
            $request->getRouter()->redirect('/admin/users');
        }

        $content = View::render('admin/modules/users/delete', [
            'nome' => $obUser->nome,
            'email' => $obUser->email,
        ]);

        return parent::getPanel('Excluir usuário > MVC', $content, 'users');
    }

    public static function setDeleteUser($request, $id) {
        $obUser = EntityUser::getUserById($id);

        if (!$obUser instanceof EntityUser) {
            $request->getRouter()->redirect('/admin/users');
        }

        $obUser->excluir();

        $request->getRouter()->redirect('/admin/users?status=deleted');
    }
}
