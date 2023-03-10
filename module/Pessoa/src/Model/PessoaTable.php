<?php

namespace Pessoa\Model;

use RuntimeException;
use Laminas\Db\ResultSet\ResultSet;
use Laminas\Db\Sql\Select;
use Laminas\Db\TableGateway\TableGatewayInterface;
use Laminas\Paginator\Adapter\DbSelect;
use Laminas\Paginator\Paginator;
use Laminas\Db\Sql\Sql;

class PessoaTable {
    private $tableGateway;

    public function __construct(TableGatewayInterface $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll($paginated = false, $ordenacao = null)
    {
        if ($paginated) {

            return $this->fetchPaginatedResults($ordenacao);
        }

        return $this->tableGateway->select();
    }
    
    private function fetchPaginatedResults($ordenacao)
    {
        // Create a new Select object for the table:
        $select = new Select($this->tableGateway->getTable());
        $select ->join(
                ['tp' => 'tipopessoa'],     // join table with alias
                'pessoa.tipo = tp.id',  // join expression
                ['datanascimento' => new \Laminas\Db\Sql\Expression("to_char(datanascimento, 'DD/MM/YYYY')"),
                 'descr' => 'descr'
                ]
        );
        $select ->join(
            ['est' => 'estado'],     // join table with alias
            'pessoa.estadonascimento = est.id',  // join expression
            ['descrestado' => 'descr']
        );

        // ordenação
        if (trim($ordenacao) != ""){
            $select->order($ordenacao);
        } else {
            $select->order('id ASC');
        }   

        // Create a new result set based on the Pessoa entity:
        $resultSetPrototype = new ResultSet();
        $resultSetPrototype->setArrayObjectPrototype(new Pessoa());

        // Create a new pagination adapter object:
        $paginatorAdapter = new DbSelect(
            // our configured select object:
            $select,
            // the adapter to run it against:
            $this->tableGateway->getAdapter(),
            // the result set to hydrate:
            $resultSetPrototype
        );

        return new Paginator($paginatorAdapter);

    }

    public function getPessoa($id)
    {
        $id = (int) $id;
        $rowset = $this->tableGateway->select(['id' => $id]);
        $row = $rowset->current();
        if (! $row) {
            throw new RuntimeException(sprintf(
                'Could not find row with identifier %d',
                $id
            ));
        }

        return $row;
    }    

    public function savePessoa(Pessoa $pessoa)
    {
        $data = [];
        $id = (int) $pessoa->id;

        if ($id === 0) {
            $data = [
                'nome'  => $pessoa->nome,
                'tipo' => $pessoa->tipo,
                'rg' => $pessoa->rg,
                'cpfcnpj' => $pessoa->cpfcnpj,
                'datanascimento' => $pessoa->datanascimento,
                'localnascimento' => $pessoa->localnascimento,
                'estadonascimento' => $pessoa->estadonascimento,
            ];
            $this->tableGateway->insert($data);
            return;
        } else {
            $data = [
                'nome'  => $pessoa->nome,
                'tipo' => $pessoa->tipo,
                'rg' => $pessoa->rg,
                'cpfcnpj' => $pessoa->cpfcnpj,
                'datanascimento' => $pessoa->datanascimento,
                'localnascimento' => $pessoa->localnascimento,
                'estadonascimento' => $pessoa->estadonascimento,
            ];
        }

        try {
            $this->getPessoa($id);
        } catch (RuntimeException $e) {
            throw new RuntimeException(sprintf(
                'Cannot update album with identifier %d; does not exist',
                $id
            ));
        }

        $this->tableGateway->update($data, ['id' => $id]);
    } 
    
    public function deletePessoa($id)
    {
        $this->tableGateway->delete(['id' => (int) $id]);
    }    

    public function filtroTeste($ordenacao){
        $select = new Select($this->tableGateway->getTable());
        $select->order('id ASC');
        return new DbSelect(
            // our configured select object:
            $select,
            // the adapter to run it against:
            $this->tableGateway->getAdapter()
        );
    }

    public function getContatosPessoa($id){
        $sql    = new Sql($this->tableGateway->getAdapter());
        $select = $sql->select();
        $select->from('contato')->where(['id_pessoa' => $id]);

        $statement = $sql->prepareStatementForSqlObject($select);
        $results = $statement->execute();            

        $resultSet = new ResultSet;
        return $resultSet->initialize($results);
    }
}