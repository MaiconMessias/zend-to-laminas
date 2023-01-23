<?php

namespace Login;

use Laminas\ModuleManager\Feature\ConfigProviderInterface;

 class Module implements ConfigProviderInterface {

    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    public function getControllerConfig()
    {
        return [
            'factories' => [
                Controller\LoginController::class => function($container) {
                    return new Controller\LoginController();
                },
            ],
        ];
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Laminas\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }


 }