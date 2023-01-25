<?php

namespace Login\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Login\Form\LoginForm;
use Laminas\Db\Adapter\Adapter as DbAdapter;
use Laminas\Authentication\Adapter\DbTable\CallbackCheckAdapter as AuthAdapter;
use Login\Model\Login;
use Laminas\Session\Container;
use Laminas\Authentication\AuthenticationService;

//session_start();
class LoginController extends AbstractActionController {

    private $auth;

    public function __construct()
    {
        $this->auth = new AuthenticationService();
    }

    public function indexAction()
    {
        $form = new LoginForm();
        //$form->get('submit')->setValue('Confirmar');

        $request = $this->getRequest();

        if (! $request->isPost()) {
            return ['form' => $form];
        }

        // Validações
        $login = new Login();
        $form->setInputFilter($login->getInputFilter());
        $form->setData($request->getPost());

        if (! $form->isValid()) {
            // retorna o formulario com messagens de erro
            return ['form' => $form];
        }

        $dbAdapter = new DbAdapter([
            'driver'   => 'Pdo-pgsql',
            'database' => 'pgsql:host=localhost;port=5432;dbname=agenda;user=postgres;password=n',
        ]);

        $authAdapter = new AuthAdapter(
            $dbAdapter,
            'users',
            'username',
            'password'
        );
        $authAdapter
            ->setIdentity($request->getPost()['username'])
            ->setCredential($request->getPost()['password']);
    
        // Perform the authentication query, saving the result
        $result = $authAdapter->authenticate();

        $this->auth->authenticate($authAdapter);

        if ($result->isValid()) {    
            echo 'Bem vindo(a) ' . $result->getIdentity() . "\n\n"; 
            print_r($authAdapter->getResultRowObject());
            $form->get('username')->setValue('logado');
            $form->get('password')->setValue('logado');
            //session_destroy();
            return $this->redirect()->toRoute('application');

            //return ['form' => $form];        
        } else {
                echo 'Usuario e ou Senha não encontrados';
                return ['form' => $form];        
        }
    }

    public function logoutAction(){
        $this->auth->clearIdentity();
        session_destroy();
        return $this->redirect()->toRoute('login');
    }
}