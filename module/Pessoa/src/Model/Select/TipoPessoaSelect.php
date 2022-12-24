<?php

    namespace Pessoa\Model\Select;

    use Laminas\Db\Adapter\Adapter as DbAdapter;
    use Laminas\Db\Sql\Sql;
    use Laminas\Db\ResultSet\ResultSet;

class TipoPessoaSelect {
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

    public function getTipoPessoa(){
        $sql    = new Sql($this->dbAdapter);
        $select = $sql->select();
        $select->from('tipopessoa')
        ->columns([
            'id' => 'id',
            'descr' => 'descr'
        ]);
        $statement = $sql->prepareStatementForSqlObject($select);
        $results = $statement->execute();            

        $resultSet = new ResultSet;
        return $resultSet->initialize($results);
    }

    public function arrayTipoPessoas(){
        $tipopessoas = $this->getTipoPessoa();

        $data = [];
        $data[0] = "-- Selecione um Tipo de Pessoa --";
        foreach ($tipopessoas as $tipopessoa){
            //$data[$tipopessoa->id] = $tipopessoa->descr;
            $data[$tipopessoa->id] = [
                'value' => $tipopessoa->id,
                'label' => $tipopessoa->descr,
                //'selected' => $tipopessoa->id == 2 ? true : false,
            ];
        }  
        
        return $data;
    }

}
