<?php
/**
 * Created by PhpStorm.
 * User: Serhii Shnurenko
 * Date: 11/14/16
 * Time: 3:47 PM
 */

namespace DataGrid\Controllers;


use DataGrid\Forms\DishForm;
use DataGrid\Forms\IndexForm;
use DataGrid\Models\DataGridItem;
use DataGrid\Models\DataGridTable;
use Zend\Db\Sql\Where;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class DataGridController extends AbstractActionController
{
    private $table;

    public function __construct(DataGridTable $table)
    {
        $this->table = $table;
    }

    public function indexAction()
    {
        $form = $this->createFormWithFilter();
        $request = $this->getRequest();
        if(!$request->isPost()){

            $data = $this->table->fetchAll();

            return new ViewModel([
                'dishes' => $data,
                'form' => $form
            ]);
        }
        //adding filtering elements
        //todo: Think about decreasing number of for loops
        $columnTypes = $this->table->getColumnTypes();
        $paramTypes = array();
        foreach ($columnTypes as $columnType) {
            $paramTypes[$columnType['field']]=$columnType['type'];
        }

        $filterParams = $request->getPost()->getArrayCopy();
        unset($filterParams['submit']);
        $paramNames = array_keys($filterParams);

        $where = $this->formWhereCondition($filterParams,$paramNames,$paramTypes);
        $data = $this->table->fetchSelected($where);

        return new ViewModel([
            'dishes' => $data,
            'form' => $form
        ]);



    }

    private function createFormWithFilter(){
        $form = new IndexForm();

        //adding filtering elements
        $columnTypes = $this->table->getColumnTypes();
        foreach($columnTypes as $column){
            if($column['field']==='category'){
                continue; //will set category control in view
            }
            $form->addElement($column['type'],$column['field'],'h',[]);
        }
        return $form;
    }

    private function formWhereCondition($filterParams, $paramNames, $paramTypes){
        $where = new Where();
        foreach($paramNames as $paramName){
            if(strpos(strtolower($paramTypes[$paramName]),'varchar')!==false){
                $where->like($paramName,strtolower('%'.$filterParams[$paramName].'%'));
            }
            if(strpos(strtolower($paramTypes[$paramName]),'int')!==false){
                $where->in($paramName,$filterParams[$paramName]);
            }
            if(strpos(strtolower($paramTypes[$paramName]),'float')!==false){
                if(empty($filterParams[$paramName])) continue;
                $minmax = explode('-',$filterParams[$paramName]);
                $where->between($paramName,$minmax[0],$minmax[1]);
            }
        }
        return $where;
    }



    public function addAction()
    {
        $form = new DishForm();
        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();

        if(!$request->isPost()){
            return ['form'=>$form];
        }

        $dish = new DataGridItem();
        $form->setInputFilter($dish->getInputFilter());
        $form->setData($request->getPost());

        if(!$form->isValid()){
            return ['form'=>$form];
        }

        $dish->exchangeArray($form->getData());
        $this->table->saveItem($dish);
        return $this->redirect()->toRoute('datagrid');
    }

    public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id',0);
        if(null===$id){
            return $this->redirect()->toRoute('datagrid',['action'=>'add']);
        }

        try{
            $dish = $this->table->getItem($id);
        }catch(\Exception $e){
            return $this->redirect()->toRoute('datagrid',['action'=>'index']);
        }
        $form = new DishForm();

        $form->bind($dish);

        $request = $this->getRequest();
        $viewData = ['id'=>$id, 'form'=>$form];

        if(!$request->isPost()){
            return $viewData;
        }

        $form->setInputFilter($dish->getInputFilter());

        $form->setData($request->getPost());

        if(!$form->isValid()){
            return $viewData;
        }
        //don't know why hydrator($form->getData()) haven't worked so managed it with hands
        $dish->exchangeArray($request->getPost()->getArrayCopy());

        $this->table->saveItem($dish);

        return $this->redirect()->toRoute('datagrid',['action'=>'index']);
    }

    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id',0);
        if (!$id){
            return $this->redirect()->toRoute('datagrid');
        }

        $request = $this->getRequest();
        if($request->isPost()){
            $del = $request->getPost('del','No');
            if($del=='Yes'){
                $id = (int)$request->getPost('id');
                $this->table->deleteItem($id);
            }
            return $this->redirect()->toRoute('datagrid');

        }
        return [
            'id'=>$id,
            'dish'=>$this->table->getItem($id)
        ];
    }

    public function deleteselectedAction()
    {
        $ids = (array)$this->params()->fromPost('todo_with');

        if(empty($ids)){
            return $this->redirect()->toRoute('datagrid');
        }

        foreach($ids as $id){
            $this->table->deleteItem($id);
        }

        return $this->redirect()->toRoute('datagrid');
    }


}