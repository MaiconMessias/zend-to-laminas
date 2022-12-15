<?php

namespace Contato\Form;

use Laminas\Form\Form;

class ContatoForm extends Form
{
    public function __construct($name = null)
    {
        // We will ignore the name provided to the constructor
        parent::__construct('contato');

        $this->add([
            'name' => 'id',
            'type' => 'hidden',
        ]);
        $this->add([
            'name' => 'contato',
            'type' => 'text',
            'options' => [
                'label' => 'Contato',
            ],
        ]);
        $this->add([
            'name' => 'id_pessoa',
            'type' => 'select',
            /*'options' => [
                'label' => 'Id Pessoa',
                //'value_options' => $this->getValueOptions(),
                'value_options' => [
                    '2' => 'Adele',
                    '3' => 'English',
                    '4' => 'Japanese',
                    '5' => 'Chinese',
                ],
            ],*/
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

    /*public function getValueOptions(){
        $data = [
            '2' => 'Adele',
            '3' => 'English',
            '4' => 'Japanese',
            '5' => 'Chinese',
        ];
        return $data;
    }*/
}