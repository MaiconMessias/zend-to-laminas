<?php

    namespace Pessoa;
    
    use Laminas\Router\Http\Segment;
            
    return [
        // The following section is new and should be added to your file:
        'router' => [
            'routes' => [
                'pessoa' => [
                    'type'    => Segment::class,
                    'options' => [
                        'route' => '/pessoa[/:action[/:id]]',
                        'constraints' => [
                            'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                            'id'     => '[0-9]+',
                        ],
                        'defaults' => [
                            'controller' => Controller\PessoaController::class,
                            'action'     => 'index',
                        ],
                    ],
                ],
                'filtropessoa' => [
                    'type'    => Segment::class,
                    'options' => [
                        'route' => '/pessoa[/:action[/:id][/:ordenar]]',
                        'constraints' => [
                            'action'      => '[a-zA-Z][a-zA-Z0-9_-]*',
                            'ordenar'     => '[0-9]+',
                        ],
                        'defaults' => [
                            'controller' => Controller\PessoaController::class,
                            'action'     => 'index',
                        ],
                    ],
                ],
            ],
        ],        
        'view_manager' => [
            'template_map' => [
                'view/pessoa/pessoa' => __DIR__ . '/../view/pessoa/pessoa/index.phtml',
            ],
            'template_path_stack' => [
                //'pessoa' => __DIR__ . '/../view',
                __DIR__ . '/../view',
            ],
        ],        
    ];
