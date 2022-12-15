<?php

namespace Login\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Login\Form\LoginForm;
use Laminas\Db\Adapter\Adapter as DbAdapter;
use Laminas\Authentication\Adapter\DbTable\CallbackCheckAdapter as AuthAdapter;
use Login\Model\Login;

class LoginController extends AbstractActionController {

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
        
        if (! null == $authAdapter->getResultRowObject()){   
            echo 'Bem vindo(a) ' . $result->getIdentity() . "\n\n"; 
            print_r($authAdapter->getResultRowObject());
            $form->get('username')->setValue('logado');
            $form->get('password')->setValue('logado');
            return $this->redirect()->toRoute('application');
        } else {
            echo 'Usuario e ou Senha não encontrados';
            return ['form' => $form];        
        }
    }
}