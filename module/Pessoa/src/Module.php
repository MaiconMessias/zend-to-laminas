<?php

namespace Pessoa;

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
                Model\PessoaTable::class => function($container) {
                    $tableGateway = $container->get(Model\PessoaTableGateway::class);
                    return new Model\PessoaTable($tableGateway);
                },
                Model\PessoaTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\Pessoa());
                    return new TableGateway('pessoa', $dbAdapter, null, $resultSetPrototype);
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
                Controller\PessoaController::class => function($container) {
                    return new Controller\PessoaController(
                        $container->get(Model\PessoaTable::class)
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