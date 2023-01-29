<?php

namespace Pessoa\Model;

// Add the following import statements:
use DomainException;
use Laminas\Filter\StringTrim;
use Laminas\Filter\StripTags;
use Laminas\Filter\ToInt;
use Laminas\InputFilter\InputFilter;
use Laminas\InputFilter\InputFilterAwareInterface;
use Laminas\InputFilter\InputFilterInterface;
use Laminas\Validator\StringLength;
use Laminas\Validator\NotEmpty;
use Pessoa\Validators\SelectOptionsValidator;

class Pessoa implements InputFilterAwareInterface{

    public $id;
    public $nome;
    public $tipo;
    public $rg;
    public $cpfcnpj;
    public $datanascimento;
    public $localnascimento;
    public $estadonascimento;
    public $descr;
    public $descrestado;
     // Add this property:
     private $inputFilter;


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

    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new DomainException(sprintf(
            '%s does not allow injection of an alternate input filter',
            __CLASS__
        ));
    }

    public function getInputFilter()
    {
        if ($this->inputFilter) {
            return $this->inputFilter;
        }

        $inputFilter = new InputFilter();

        $inputFilter->add([
            'name' => 'id',
            'required' => true,
            'filters' => [
                ['name' => ToInt::class],
            ],
        ]);

        $inputFilter->add([
            'name' => 'nome',
            'required' => true,
            'filters' => [
                ['name' => StripTags::class],
                ['name' => StringTrim::class],
            ],
            'validators' => [
                [
                    'name' => NotEmpty::class,
                    'options' => [
                        'encoding' => 'UTF-8',
                        'messages' => [
                            // You must specify which validator error messageyou are overriding
                            NotEmpty::IS_EMPTY => 'Preencha o campo Nome'
                        ]
                    ],
                ],
                [
                    'name' => StringLength::class,
                    'options' => [
                        'encoding' => 'UTF-8',
                        'min' => 2,
                        'max' => 100,
                        'messages' => [
                            // You must specify which validator error messageyou are overriding
                            StringLength::TOO_SHORT => 'O campo Nome precisa ter de 2 a 100 caracteres'
                        ],
                    ],
                ],
            ]
        ]);

        $inputFilter->add([
            'name' => 'tipo',
            'required' => true,
            'filters' => [
                ['name' => ToInt::class],
            ],
            'validators' => [
                [
                    'name' => SelectOptionsValidator::class,
                ],                
            ]
        ]);


        $this->inputFilter = $inputFilter;
        return $this->inputFilter;
    }    
 }