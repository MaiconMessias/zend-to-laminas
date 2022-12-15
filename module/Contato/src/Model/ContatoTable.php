<?php
/*SELECT C.ID AS ID, C.CONTATO AS CONTATO, P.NOME AS NOME_PESSOA
FROM CONTATO C JOIN PESSOA P
  ON (C.ID_PESSOA = P.ID)*/

namespace Contato\Model;

use RuntimeException;
use Laminas\Db\ResultSet\ResultSet;
use Laminas\Db\TableGateway\TableGatewayInterface;
use Laminas\Paginator\Adapter\DbSelect;
use Laminas\Paginator\Paginator;
use Laminas\Db\Sql\Sql;


class ContatoTable {
    private $tableGateway;

    public function __construct(TableGatewayInterface $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll($paginated = false)
    {
        /**
         * Médodo 1
         */
        //return $this->tableGateway->select();
        /**
         * Médodo 2
         */
        /*$select = new Select($this->tableGateway->getTable());
        $select 
                ->join(
                ['p' => 'pessoa'],     // join table with alias
                'contato.id_pessoa = p.id'  // join expression
        );


        $resultSetPrototype = new ResultSet();
        $resultSetPrototype->setArrayObjectPrototype(new Contato());

        $results = new DbSelect(
            // our configured select object:
            $select,
            // the adapter to run it against:
            $this->tableGateway->getAdapter(),
            // the result set to hydrate:
            $resultSetPrototype
        );

        return new Paginator($results);*/

        /**
         * Médodo 3
         */
        $sql    = new Sql($this->tableGateway->getAdapter());
        $select = $sql->select();
        $select ->from('contato')
                ->join(
                ['p' => 'pessoa'],     // join table with alias
                'contato.id_pessoa = p.id',  // join expression
                ['nomepessoa' => 'nome']
        );

        /*$statement = $sql->prepareStatementForSqlObject($select);
        $results = $statement->execute();*/

        $resultSetPrototype = new ResultSet;
        $resultSetPrototype->setArrayObjectPrototype(new Contato());

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

    public function getContato($id)
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

    public function saveContato(Contato $contato)
    {
        $data = [];
        $id = (int) $contato->id;

        if ($id === 0) {
            $data = [
                'contato' => $contato->contato,
                'id_pessoa' => $contato->id_pessoa,
            ];
            $this->tableGateway->insert($data);
            return;
        } else {
            $data = [
                'contato' => $contato->contato,
                'id_pessoa' => $contato->id_pessoa,
            ];
        }

        try {
            $this->getContato($id);
        } catch (RuntimeException $e) {
            throw new RuntimeException(sprintf(
                'Cannot update contato with identifier %d; does not exist',
                $id
            ));
        }

        $this->tableGateway->update($data, ['id' => $id]);
    }   
    
    public function deleteContato($id)
    {
        $this->tableGateway->delete(['id' => (int) $id]);
    }    
    
}