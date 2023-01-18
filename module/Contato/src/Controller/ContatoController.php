<?php

  namespace Contato\Controller;

  use Laminas\Mvc\Controller\AbstractActionController;
  use Laminas\View\Model\ViewModel;
  use Contato\Model\ContatoTable;
  use Contato\Form\ContatoForm;
  use Contato\Model\Contato;
  use Contato\Model\Select\ContatoSelect;
  use Psr\Container\ContainerInterface;
  
  class ContatoController extends AbstractActionController {
      // Add this property:
      private $table;

     // Add this constructor:
     public function __construct(ContatoTable $table)
     {
         $this->table = $table;
     }

     // Lista de Contatos
     public function indexAction()
     {
        $paginator = $this->table->fetchAll(true);

        // Set the current page to what has been passed in query string,
        // or to 1 if none is set, or the page is invalid:
        $page = (int) $this->params()->fromQuery('page', 1);
        $page = ($page < 1) ? 1 : $page;
        $paginator->setCurrentPageNumber($page);
    
        // Set the number of items per page to ..:
        $paginator->setItemCountPerPage(3);

         return new ViewModel(['paginator' => $paginator]);
     }

     // Adiciona contato
     public function addAction(){
        $form = new ContatoForm();
        $form->get('submit')->setValue('Adicionar');
        
        $contatoselect = new ContatoSelect();
        //$pessoas = $this->table->fetchAll();
        $id_pessoa = $form->get('id_pessoa');

        //print_r($data); 
        $id_pessoa->setLabel('Pessoa');   
        $id_pessoa->setValueOptions(
            //$data
            $contatoselect->arrayPessoas()
        );

        $request = $this->getRequest();
        
        if (! $request->isPost()) {
            return ['form' => $form];
        }

        $contato = new Contato();        
        $form->setData($request->getPost());
        
        if (! $form->isValid()) {
            return ['form' => $form];
        }

        $contato->exchangeArray($form->getData());
        $this->table->saveContato($contato);
        return $this->redirect()->toRoute('contato');        
     }

     // Formulário de exclusão de contato
     public function editdeleteAction()
     {
         $id = (int) $this->params()->fromRoute('id', 0);

         if (0 === $id) {
             return $this->redirect()->toRoute('contato', ['action' => 'add']);
         }
 
         // Retrieve the pessoa with the specified id. Doing so raises
         // an exception if the pessoa is not found, which should result
         // in redirecting to the landing page.
         try {
             $contato = $this->table->getContato($id);
         } catch (\Exception $e) {
             return $this->redirect()->toRoute('contato', ['action' => 'index']);
         }
 
         $form = new ContatoForm();
         $form->bind($contato);
         $form->get('submit')->setAttribute('value', 'Editar');

         $contatoselect = new ContatoSelect();
         $id_pessoa = $form->get('id_pessoa');
         $id_pessoa->setLabel('Pessoa');   
         $id_pessoa->setValueOptions(
             //$data
             $contatoselect->arrayPessoas()
         );
 
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
             $this->table->saveContato($contato);
         } catch (\Exception $e) {
         }
 
         // Redirect to pessoa list
         return $this->redirect()->toRoute('contato', ['action' => 'index']);        
     }  
     
     public function deleteAction()
     {
         $id = (int) $this->params()->fromRoute('id', 0);
         if (!$id) {
             return $this->redirect()->toRoute('contato');
         }
 
         $request = $this->getRequest();
         if ($request->isPost()) {
             $del = $request->getPost('del', 'Não');
 
             if ($del == 'Sim') {
                 //$id = (int) $request->getPost('id');
                 $this->table->deleteContato($id);
             }
 
             // Redirect to list of pesssoas
             return $this->redirect()->toRoute('contato');
         }
 
         return [
             'id'    => $id,
             'contato' => $this->table->getContato($id),
         ];
     }
 
     
  }  