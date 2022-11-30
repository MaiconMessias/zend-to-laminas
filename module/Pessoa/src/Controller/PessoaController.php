<?php

namespace Pessoa\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Pessoa\Model\PessoaTable;
use Pessoa\Form\PessoaForm;
use Pessoa\Model\Pessoa;

class PessoaController extends AbstractActionController {

    // Add this property:
    private $table;

    // Add this constructor:
    public function __construct(PessoaTable $table)
    {
        $this->table = $table;
    }

    public function indexAction()
    {
        return new ViewModel([
            'pessoas' => $this->table->fetchAll(),
        ]);        
    }

    public function addAction()
    {
        $form = new PessoaForm();
        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();

        if (! $request->isPost()) {
            return ['form' => $form];
        }

        $pessoa = new Pessoa();
        //$form->setInputFilter($pessoa->getInputFilter());
        $form->setData($request->getPost());

        if (! $form->isValid()) {
            return ['form' => $form];
        }

        $pessoa->exchangeArray($form->getData());
        $this->table->savePessoa($pessoa);
        return $this->redirect()->toRoute('pessoa');        
    }

    public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);

        if (0 === $id) {
            return $this->redirect()->toRoute('pessoa', ['action' => 'add']);
        }

        // Retrieve the album with the specified id. Doing so raises
        // an exception if the album is not found, which should result
        // in redirecting to the landing page.
        try {
            $pessoa = $this->table->getPessoa($id);
        } catch (\Exception $e) {
            return $this->redirect()->toRoute('pessoa', ['action' => 'index']);
        }

        $form = new PessoaForm();
        $form->bind($pessoa);
        $form->get('submit')->setAttribute('value', 'Edit');

        $request = $this->getRequest();
        $viewData = ['id' => $id, 'form' => $form];

        if (! $request->isPost()) {
            return $viewData;
        }

        //$form->setInputFilter($pessoa->getInputFilter());
        $form->setData($request->getPost());

        if (! $form->isValid()) {
            return $viewData;
        }

        try {
            $this->table->savePessoa($pessoa);
        } catch (\Exception $e) {
        }

        // Redirect to album list
        return $this->redirect()->toRoute('pessoa', ['action' => 'index']);        
    }

    public function deleteAction()
    {
    }
}
