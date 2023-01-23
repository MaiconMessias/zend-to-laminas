<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Laminas\Authentication\AuthenticationService as AuthenticationService;

class IndexController extends AbstractActionController
{
    private $auth;

    public function __construct()
    {
        $this->auth = new AuthenticationService();
        // Verifica se o usurário está logado
        if (! $this->auth->hasIdentity())
            return $this->redirect()->toRoute('login');
    }

    public function indexAction()
    {
        return new ViewModel();
    }

}
