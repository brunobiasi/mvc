<?php

namespace App\Model\Entity;

use App\Db\Database;

class Testimony {
    public $id;
    public $nome;
    public $mensagem;
    public $data;

    public function cadastrar() {
        $this->data = date('Y-m-d H:i:s');

        $this->id = (new Database('depoimentos'))->insert([
            'nome' => $this->nome,
            'mensagem' => $this->mensagem,
            'data' => $this->data,
        ]);
        return true;
    }

    public static function getTestimonies($where = null, $order = null, $limit = null, $fields = '*') {
        return (new Database('depoimentos'))->select($where, $order, $limit, $fields);
    }
}
