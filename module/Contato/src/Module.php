<?php

namespace Contato;

use Laminas\Db\Adapter\AdapterInterface;
use Laminas\Db\ResultSet\ResultSet;
use Laminas\ModuleManager\Feature\ConfigProviderInterface;
use Laminas\Db\TableGateway\TableGateway;


 class Module implements ConfigProviderInterface {
    /**
     * Configurações das factories laminas
     */
    public function getServiceConfig()
    {
        return [
            'factories' => [
                Model\ContatoTable::class => function($container) {
                    $tableGateway = $container->get(Model\ContatoTableGateway::class);
                    return new Model\ContatoTable($tableGateway);
                },
                Model\ContatoTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\Contato());
                    return new TableGateway('contato', $dbAdapter, null, $resultSetPrototype);
                },
            ],
        ];
    }

    
    /** fim */
    const VERSION = '3.1.3';
    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    // Add this method:
    public function getControllerConfig()
    {
        return [
            'factories' => [
                Controller\ContatoController::class => function($container) {
                    return new Controller\ContatoController(
                        $container->get(Model\ContatoTable::class)
                    );
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