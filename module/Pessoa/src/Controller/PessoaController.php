<?php

namespace Pessoa\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Pessoa\Model\PessoaTable;
use Pessoa\Form\PessoaForm;
use Pessoa\Model\Pessoa;
use Laminas\Form\Form;

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
        
        if (null !== $this->getRequest()) {
            $request = $this->getRequest();
            // Implementar atribuição dos valores dos campos em outro arquivo
            $campo = $request->getPost()['campo'] == 0 ? 'id' : 'nome';
            $ordem = $request->getPost()['ordenacao'] == 0 ? 'ASC' : 'DESC';
            $ordenacao = $campo . ' ' . $ordem;
        }
        

        // Grab the paginator from the PessoaTable:
        $paginator = $this->table->fetchAll(true, $ordenacao);
    
        // Set the current page to what has been passed in query string,
        // or to 1 if none is set, or the page is invalid:
        $page = (int) $this->params()->fromQuery('page', 1);
        $page = ($page < 1) ? 1 : $page;
        $paginator->setCurrentPageNumber($page);
    
        // Set the number of items per page to ..:
        $paginator->setItemCountPerPage(3);

        return new ViewModel(['paginator' => $paginator]);
    }

    public function addAction()
    {
        $form = new PessoaForm();
        $form->get('submit')->setValue('Adicionar');

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

    public function editdeleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);

        if (0 === $id) {
            return $this->redirect()->toRoute('pessoa', ['action' => 'add']);
        }

        // Retrieve the pessoa with the specified id. Doing so raises
        // an exception if the pessoa is not found, which should result
        // in redirecting to the landing page.
        try {
            $pessoa = $this->table->getPessoa($id);
        } catch (\Exception $e) {
            return $this->redirect()->toRoute('pessoa', ['action' => 'index']);
        }

        $form = new PessoaForm();
        $form->bind($pessoa);
        $form->get('submit')->setAttribute('value', 'Editar');

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

        // Redirect to pessoa list
        return $this->redirect()->toRoute('pessoa', ['action' => 'index']);        
    }

    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('pessoa');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'Não');

            if ($del == 'Sim') {
                //$id = (int) $request->getPost('id');
                try {
                    $this->table->deletePessoa($id);
                } catch (\Exception $e) {
                    echo $e->getMessage();
                    return;    
                }
            }

            // Redirect to list of pesssoas
            return $this->redirect()->toRoute('pessoa');
        }

        return [
            'id'    => $id,
            'nome' => $this->table->getPessoa($id),
        ];
    }

    public function testefiltroAction(){
        $request = $this->getRequest();
        
        $form = new Form('form-filtro');
        $form->setData($request->getPost());
        if (! $request->isPost()) {
            return ['form' => $form];
        }
        $campo = $request->getPost()['campo'] == 0 ? 'id' : 'nome';
        $ordem = $request->getPost()['ordenacao'] == 0 ? 'ASC' : 'DESC';
        $ordenacao = $campo . ' ' . $ordem;

        $result = $this->table->filtroTeste($ordenacao);
        return $this->redirect()->toRoute('pessoa', ['action' => 'index', 'paginator' => $result]);
        /*echo $request->getPost()['campo'] == 0 ? 'id' : 'nome'; 
        echo $request->getPost()['ordenacao'];  */
    }
}
