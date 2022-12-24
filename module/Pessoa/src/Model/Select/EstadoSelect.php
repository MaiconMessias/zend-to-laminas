<?php

    namespace Pessoa\Model\Select;

    use Laminas\Db\Adapter\Adapter as DbAdapter;
    use Laminas\Db\Sql\Sql;
    use Laminas\Db\ResultSet\ResultSet;

class EstadoSelect {
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

    public function getEstados(){
        $sql    = new Sql($this->dbAdapter);
        $select = $sql->select();
        $select->from('estado')
        ->columns([
            'id' => 'id',
            'descr' => 'descr'
        ]);
        $statement = $sql->prepareStatementForSqlObject($select);
        $results = $statement->execute();            

        $resultSet = new ResultSet;
        return $resultSet->initialize($results);
    }

    public function arrayEstados(){
        $estados = $this->getEstados();

        $data = [];
        $data[0] = "-- Selecione um Estado --";     
        foreach ($estados as $estado){
            $data[$estado->id] = $estado->descr;
        }  
        return $data;
    }

}
