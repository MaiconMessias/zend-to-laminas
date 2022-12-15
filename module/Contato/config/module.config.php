<?php

    namespace Contato;
    
    use Laminas\Router\Http\Segment;
            
    return [
        // The following section is new and should be added to your file:
        'router' => [
            'routes' => [
                'contato' => [
                    'type'    => Segment::class,
                    'options' => [
                        'route' => '/contato[/:action[/:id]]',
                        'constraints' => [
                            'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                            'id'     => '[0-9]+',
                        ],
                        'defaults' => [
                            'controller' => Controller\ContatoController::class,
                            'action'     => 'index',
                        ],
                    ],
                ],
            ],
        ],        
        'view_manager' => [
            'template_map' => [
                'view/contato/contato' => __DIR__ . '/../view/contato/contato/index.phtml',
            ],
            'template_path_stack' => [
                __DIR__ . '/../view',
            ],
        ],        
    ];
