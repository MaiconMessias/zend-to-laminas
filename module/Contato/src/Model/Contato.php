<?php

namespace Contato\Model;

class Contato {

    public $id;
    public $contato;
    public $id_pessoa;

    public function exchangeArray(array $data){
        $this->id = !empty($data['id']) ? $data['id'] : null;
        $this->contato = !empty($data['contato']) ? $data['contato'] : null;
        $this->id_pessoa = !empty($data['id_pessoa']) ? $data['id_pessoa'] : null;
        $this->nomepessoa = !empty($data['nomepessoa']) ? $data['nomepessoa'] : null;
     }

    public function getArrayCopy()
    {
        return [
            'id' => $this->id,
            'contato' => $this->contato,
            'id_pessoa' => $this->id_pessoa,
            'nomepessoa' => $this->nomepessoa,
        ];
    }
 }
