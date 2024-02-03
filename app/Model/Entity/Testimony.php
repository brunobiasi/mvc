<?php

namespace App\Model\Entity;

class Testimony {
    public $id;
    public $nome;
    public $mensagem;
    public $data;

    public function cadastrar() {
        echo '<pre>';
        print_r($this);
        echo '</pre>';
        exit;
    }
}
