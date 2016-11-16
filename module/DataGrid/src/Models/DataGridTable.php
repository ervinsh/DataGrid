<?php
/**
 * Created by PhpStorm.
 * User: Serhii Shnurenko
 * Date: 11/14/16
 * Time: 3:48 PM
 */

namespace DataGrid\Models;


use Zend\Db\TableGateway\TableGatewayInterface;

class DataGridTable
{
    private $tableGateway;

    public function __construct(TableGatewayInterface $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll()
    {
        return $this->tableGateway->select();
    }

    public function getItem($id){
        $id = (int) $id;
        $rowset = $this->tableGateway->select(['id'=>$id]);
        $row = $rowset->current();
        if (! $row) {
            throw new RuntimeException(sprintf(
                'Could not find row with identifier %d',
                $id
            ));
        }
        return $row;
    }

    /**
     * Method for adding or editing entries in db
     * pass the DataGridItem with id===0 to INSERT entry
     * else existing id to UPDATE existing entry
     * @param DataGridItem $item
     */
    public function saveItem(DataGridItem $item){
        $id = $item->id;

        $data = $item->getArrayCopy();
        //let db to use autoindexing
        unset($data['id']);

        //add new entry
        if($id==null){
            $this->tableGateway->insert($data);
            return;
        }

        //check if id  exist in table
        if (!$this->getItem($id)) {
            throw new RuntimeException(sprintf(
                '%d; does not exist',
                $id
            ));
        }

        $this->tableGateway->update($data, ['id'=>$id]);
    }

    public function deleteItem($id)
    {
        $this->tableGateway->delete(['id'=>(int)$id]);
    }
}