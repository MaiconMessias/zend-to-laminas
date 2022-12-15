<?php

    namespace Login;
    
    use Laminas\Router\Http\Segment;
                
    return [
        // The following section is new and should be added to your file:
        'router' => [
            'routes' => [
                'login' => [
                    'type'    => Segment::class,
                    'options' => [
                        'route' => '/login[/:action]',
                        'constraints' => [
                            'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        ],
                        'defaults' => [
                            'controller' => Controller\LoginController::class,
                            'action'     => 'index',
                        ],
                    ],
                ],
                'logar' => [
                    'type'    => Segment::class,
                    'options' => [
                        'route'    => '/',
                        'defaults' => [
                            'controller' => Controller\LoginController::class,
                            'action'     => 'index',
                        ],
                    ],
                ],
            ],
        ],        
        'view_manager' => [
            'template_map' => [
                'layout/layoutlogin'    =>  __DIR__ . '/../view/layout/layoutlogin.phtml',
                'view/login/login' => __DIR__ . '/../view/login/login/index.phtml',
            ],
            'template_path_stack' => [
                __DIR__ . '/../view',
            ],
        ],        
    ];
