<?php

namespace Pessoa\Model;

class Pessoa {

    public $id;
    public $nome;
    public $tipo;
    public $rg;
    public $cpfcnpj;
    public $datanascimento;
    public $localnascimento;
    public $estadonascimento;

    public function exchangeArray(array $data){
        $this->id = !empty($data['id']) ? $data['id'] : null;
        $this->nome = !empty($data['nome']) ? $data['nome'] : null;
        $this->tipo = !empty($data['tipo']) ? $data['tipo'] : null;
        $this->rg = !empty($data['rg']) ? $data['rg'] : null;
        $this->cpfcnpj = !empty($data['cpfcnpj']) ? $data['cpfcnpj'] : null;
        $this->datanascimento = !empty($data['datanascimento']) ? $data['datanascimento'] : null;
        $this->localnascimento = !empty($data['localnascimento']) ? $data['localnascimento'] : null;
        $this->estadonascimento = !empty($data['estadonascimento']) ? $data['estadonascimento'] : null;
        $this->descr = !empty($data['descr']) ? $data['descr'] : null;
        $this->descrestado = !empty($data['descrestado']) ? $data['descrestado'] : null;
     }

    public function getArrayCopy()
    {
        return [
            'id' => $this->id,
            'nome' => $this->nome,
            'tipo' => $this->tipo,
            'rg' => $this->rg,
            'cpfcnpj' => $this->cpfcnpj,
            'datanascimento' => $this->datanascimento,
            'localnascimento' => $this->localnascimento,
            'estadonascimento' => $this->estadonascimento,
            'descr' => $this->descr,
            'descrestado' => $this->descrestado,
        ];
    }
 }