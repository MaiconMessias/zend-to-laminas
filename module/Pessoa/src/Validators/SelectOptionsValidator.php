<?php

namespace Pessoa\Validators;

use Laminas\Validator\AbstractValidator;

class SelectOptionsValidator extends AbstractValidator
{
    const MENSAGEM = 'SELECIONE';

    protected $messageTemplates = [
        self::MENSAGEM => "Selecione um item",
    ];

    public function isValid($value)
    {
        $this->setValue($value);

        if ($value == '0') {
            $this->error(self::MENSAGEM);
            return false;
        }

        return true;
    }
}