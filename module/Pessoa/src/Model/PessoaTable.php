<?php

namespace Pessoa\Model;

use RuntimeException;
use Laminas\Db\TableGateway\TableGatewayInterface;

class PessoaTable {
    private $tableGateway;

    public function __construct(TableGatewayInterface $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll()
    {
        return $this->tableGateway->select();
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
            ];
            $this->tableGateway->insert($data);
            return;
        } else {
            $data = [
                'nome'  => $pessoa->nome,
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
    
    public function deleteAlbum($id)
    {
        $this->tableGateway->delete(['id' => (int) $id]);
    }    
}