<?php

namespace Pessoa\Model;

class Pessoa {

    public $id;
    public $nome;

    public function exchangeArray(array $data){
        $this->id = !empty($data['id']) ? $data['id'] : null;
        $this->nome = !empty($data['nome']) ? $data['nome'] : null;
     }

    public function getArrayCopy()
    {
        return [
            'id' => $this->id,
            'nome' => $this->nome,
        ];
    }
 }
