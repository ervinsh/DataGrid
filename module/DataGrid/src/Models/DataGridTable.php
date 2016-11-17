<?php
/**
 * Created by PhpStorm.
 * User: Serhii Shnurenko
 * Date: 11/14/16
 * Time: 3:48 PM
 */

namespace DataGrid\Models;


use Zend\Db\TableGateway\TableGatewayInterface;
use Zend\Db\ResultSet\ResultSet ;
use \RuntimeException;
use Zend\Db\Sql\Where;

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

    public function fetchSelected(Where $where)
    {
        return $this->tableGateway->select($where);
    }

    /**
     * @return array with types of columns field_name=>field_type
     */
    public function getColumnTypes(){
        $types = array();

        //retrieving adapter and preparing statement
        $adapter = $this->tableGateway->getAdapter();
        $statement = $adapter->createStatement('SHOW COLUMNS FROM dishes;');
        $statement->prepare();

        $result    = $statement->execute();
        $resultSet = new ResultSet;
        $resultSet->initialize($result);

        $index =0;
        foreach ($resultSet as $row){
            $types[$index]['type'] = $row->getArrayCopy()['Type'];
            $types[$index]['field'] = $row->getArrayCopy()['Field'];
            $index++;
        }

        return $types;
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