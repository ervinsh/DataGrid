<?php
/**
 * Created by PhpStorm.
 * User: Serhii Shnurenko
 * Date: 11/14/16
 * Time: 3:47 PM
 */

namespace DataGrid\Controllers;


use DataGrid\Forms\DishForm;
use DataGrid\Models\DataGridItem;
use DataGrid\Models\DataGridTable;
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
        return new ViewModel([
            'dishes' =>$this->table->fetchAll()
        ]
    );
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
}