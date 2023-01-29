<?php

namespace Pessoa\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Pessoa\Model\PessoaTable;
use Pessoa\Form\PessoaForm;
use Pessoa\Model\Pessoa;
use Laminas\Form\Form;
use Pessoa\Model\Select\TipoPessoaSelect;
use Pessoa\Model\Select\EstadoSelect;
use Laminas\Authentication\AuthenticationService as AuthenticationService;

session_start();
class PessoaController extends AbstractActionController {

    // Add this property:
    private $table;
    private $auth;

    // Add this constructor:
    public function __construct(PessoaTable $table)
    {
        $this->table = $table;
        $this->auth = new AuthenticationService();
        // Verifica se o usurário está logado
        if (! $this->auth->hasIdentity())
            return $this->redirect()->toRoute('login');
    }

    public function indexAction()
    {
        //** */ se for clicado no botao de ordenação
        $ordenar = (int) $this->params()->fromRoute('ordenar', 0);
        
        $filtro = $this->filtroordenar($ordenar, $this->getRequest());
        /** */

        // Grab the paginator from the PessoaTable:
        $paginator = $this->table->fetchAll(true, $filtro['ordenacao']);
    
        // Set the current page to what has been passed in query string,
        // or to 1 if none is set, or the page is invalid:
        $page = (int) $this->params()->fromQuery('page', 1);
        $page = ($page < 1) ? 1 : $page;
        $paginator->setCurrentPageNumber($page);
    
        // Set the number of items per page to ..:
        $paginator->setItemCountPerPage(3);

        //$filtro = array('campo' => $campo, 'ordem' => $ordem);
        return new ViewModel(['paginator' => $paginator, 'campo' => $filtro['campo'], 'ordem' => $filtro['ordem']]);
    }

    public function addAction()
    {
        $form = new PessoaForm();
        $form->get('submit')->setValue('Adicionar');

        // Select tipo pessoa
        $tipopessoaselect = new TipoPessoaSelect();
        $tipo = $form->get('tipo');

        $tipo->setValueOptions(
            $tipopessoaselect->arrayTipoPessoas()
        );

        // select estado 
        $estadoselect = new EstadoSelect();
        $estadonasc = $form->get('estadonascimento');

        $estadonasc->setValueOptions(
            $estadoselect->arrayEstados()
        );

        $request = $this->getRequest();

        if (! $request->isPost()) {
            return ['form' => $form];
        }

        $pessoa = new Pessoa();
        $form->setInputFilter($pessoa->getInputFilter());
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

        // Select tipo pessoa
        $tipopessoaselect = new TipoPessoaSelect();
        $tipo = $form->get('tipo');

        $tipo->setValueOptions(
            $tipopessoaselect->arrayTipoPessoas()
        );
        
        // select estado 
        $estadoselect = new EstadoSelect();
        $estadonasc = $form->get('estadonascimento');

        $estadonasc->setValueOptions(
            $estadoselect->arrayEstados()
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

    public function filtroordenar($ordenar, $request){
        if (1 === $ordenar) {    
            $request = $this->getRequest();

            // Campo com a ordenacao requerida
            $ordenacao = $request->getPost()['campo'] . ' ' . $request->getPost()['ordenacao'];

            // Setada as variaveis do filtro na sessão
            $_SESSION['campo'] = $request->getPost()['campo'];
            $_SESSION['ordenacao'] = $request->getPost()['ordenacao'];
            // setado os valores dos campos dos componentes de select
            $campo = $request->getPost()['campo'];  
            $ordem = $request->getPost()['ordenacao'];                                                  
        } else {
            // se ordenação
            if (isset($_SESSION['campo']) && isset($_SESSION['ordenacao'])) {
                $ordenacao = $_SESSION['campo'] . ' ' . $_SESSION['ordenacao'];
                $campo = $_SESSION['campo'];  
                $ordem = $_SESSION['ordenacao'];                                                  
            }
        }
        
        $filtro = array(
                        'ordenacao' => $ordenacao,
                        'campo' => $campo,
                        'ordem' => $ordem,
                    );

        return $filtro;
    }

    public function contatosAction(){
        $id = (int) $this->params()->fromRoute('id', 0);
        $contatos = $this->table->getContatosPessoa($id);
        return new ViewModel(['contatos' => $contatos, 'id_pessoa' => $id]);
    }
}
