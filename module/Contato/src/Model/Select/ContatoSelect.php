<?php

  namespace Contato\Model\Select;
 
  use Laminas\Db\Adapter\Adapter as DbAdapter;
  use Laminas\Db\Sql\Sql;
  use Laminas\Db\ResultSet\ResultSet;

  class ContatoSelect {
        // Add this property:
        private $dbAdapter;

        // Add this constructor:
        public function __construct()
        {
            $this->dbAdapter = new DbAdapter([
                'driver'   => 'Pdo-pgsql',
                'database' => 'pgsql:host=localhost;port=5432;dbname=agenda;user=postgres;password=n',
            ]);
        }
     
        public function getPessoas(){
            $sql    = new Sql($this->dbAdapter);
            $select = $sql->select();
            $select->from('pessoa')
            ->columns([
                'id' => 'id',
                'nome' => 'nome'
            ]);
            $statement = $sql->prepareStatementForSqlObject($select);
            $results = $statement->execute();            

            $resultSet = new ResultSet;
            return $resultSet->initialize($results);
        }

        public function arrayPessoas(){
            $pessoas = $this->getPessoas();
    
            $data = [];     
            foreach ($pessoas as $pessoa){
                $data[$pessoa->id] = $pessoa->nome;
            }  
            return $data;
        }

  }
