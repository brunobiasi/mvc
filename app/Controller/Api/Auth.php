<?php

namespace App\Controller\Api;

use App\Model\Entity\User;
use Exception;
use Firebase\JWT\JWT;

class Auth extends Api {
    public static function generateToken($request) {
        $postVars = $request->getPostVars();

        if (!isset($postVars['email']) or !isset($postVars['senha'])) {
            throw new Exception("Os campos 'email' e 'senha' são obrigatórios", 400);
        }

        $obUser = User::getUserByEmail($postVars['email']);

        if (!$obUser instanceof User) {
            throw new Exception("O usuário ou senha são inválidos", 400);
        }

        if (!password_verify($postVars['senha'], $obUser->senha)) {
            throw new Exception("O usuário ou senha são inválidos", 400);
        }

        $payload = [
            'email' => $obUser->email
        ];

        return [
            'token' => JWT::encode($payload, getenv('JWT_KEY'), 'HS256')
        ];
    }
}
