<?php

namespace Pessoa\Form;

use Laminas\Form\Form;

class PessoaForm extends Form
{
    public function __construct($name = null)
    {
        // We will ignore the name provided to the constructor
        parent::__construct('pessoa');

        $this->add([
            'name' => 'id',
            'type' => 'hidden',
        ]);
        $this->add([
            'name' => 'nome',
            'type' => 'text',
            'options' => [
                'label' => 'Nome',
            ],
        ]);
        $this->add([
            'name' => 'tipo',
            'type' => 'select',
            'options' => [
                'label' => 'Tipo',
            ],
        ]);        
        $this->add([
            'name' => 'rg',
            'type' => 'text',
            'options' => [
                'label' => 'RG',
            ],
        ]);        
        $this->add([
            'name' => 'cpfcnpj',
            'type' => 'text',
            'options' => [
                'label' => 'CPF/CNPJ',
            ],
        ]);        
        $this->add([
            'name' => 'datanascimento',
            'type' => 'date',
            'options' => [
                'label' => 'Data Nascimento',
            ],
        ]);        
        $this->add([
            'name' => 'localnascimento',
            'type' => 'text',
            'options' => [
                'label' => 'Local Nascimento',
            ],
        ]);        
        $this->add([
            'name' => 'estadonascimento',
            'type' => 'select',
            'options' => [
                'label' => 'Estado Nascimento',
            ],
        ]);        
        $this->add([
            'name' => 'submit',
            'type' => 'submit',
            'attributes' => [
                'value' => 'Enviar',
                'id'    => 'submitbutton',
            ],
        ]);
        $this->add([
            'name' => 'dialogdeletar',
            'type' => 'button',
            'attributes' => [
                'value' => 'Deletar',
                'id'    => 'deletarbutton',
            ],
        ]);

    }
}