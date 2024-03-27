<?php

namespace App\Controller\Admin;

use App\Model\Entity\User;
use App\Session\Admin\Login as SessionAdminLogin;
use App\Utils\View;

class Login extends Page {
    public static function getLogin($request, $errorMessage = null) {
        $status = !is_null($errorMessage) ? View::render('admin/login/status', [
            'mensagem' => $errorMessage
        ]) : '';

        $content = View::render('admin/login', [
            'status' => $status
        ]);

        return parent::getPage('Login > MVC', $content);
    }

    public static function setLogin($request) {
        $postVars = $request->getPostVars();
        $email = $postVars['email'] ?? '';
        $senha = $postVars['senha'] ?? '';

        $obUser = User::getUserByEmail($email);
        if (!$obUser instanceof User) {
            return self::getLogin($request, 'E-mail ou senha inválidos');
        }

        if (!password_verify($senha, $obUser->senha)) {
            return self::getLogin($request, 'E-mail ou senha inválidos');
        }

        SessionAdminLogin::login($obUser);

        $request->getRouter()->redirect('/admin');
    }

    public static function setLogout($request) {
        SessionAdminLogin::logout();

        $request->getRouter()->redirect('/admin/login');
    }
}
