<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

class Module
{
    const VERSION = '3.1.3';

    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    public static function getJanelaDeletar(){
        return __DIR__ . '/../view/window/modaldelete.phtml';
    }

}
