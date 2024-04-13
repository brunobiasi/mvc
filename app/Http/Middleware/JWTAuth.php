<?php

namespace App\Http\Middleware;

use App\Model\Entity\User;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JWTAuth {
    private function getJWTAuthUser($request) {
        $headers = $request->getHeaders();

        $jwt = isset($headers['Authorization']) ? str_replace('Bearer ', '', $headers['Authorization']) : '';

        try {
            $decode = (array)JWT::decode($jwt, new Key(getenv('JWT_KEY'), 'HS256'));
        } catch (Exception $e) {
            throw new Exception("Token inválido", 403);
        }

        $email = $decode['email'] ?? '';

        $obUser = User::getUserByEmail($email);

        return $obUser instanceof User ? $obUser : false;
    }

    private function auth($request) {
        if ($obUser = $this->getJWTAuthUser($request)) {
            $request->user = $obUser;
            return true;
        }

        throw new Exception("Acesso negado", 403);
    }

    public function handle($request, $next) {
        $this->auth($request);

        return $next($request);
    }
}
