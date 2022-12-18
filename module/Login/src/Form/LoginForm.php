<?php

namespace Login\Form;

use Laminas\Form\Form;

class LoginForm extends Form
{
    public function __construct($name = null)
    {
        // We will ignore the name provided to the constructor
        parent::__construct('login');

        $this->add([
            'name' => 'username',
            'type' => 'text',
            'options' => [
                'label' => 'Usuário',
            ]
        ]);
        $this->add([
            'name' => 'password',
            'type' => 'password',
            'options' => [
                'label' => 'Senha',
            ],
        ]);
        $this->add([
            'name' => 'submit',
            'type' => 'submit',
            'attributes' => [
                'value' => 'Confirmar',
//                'id'    => 'submitbutton',
            ],
        ]);
    }
}