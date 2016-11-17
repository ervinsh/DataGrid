<?php

namespace DataGrid\Forms;

use Zend\Form\Form;

class IndexForm extends Form
{
    public function __construct($name = null)
    {
        //setting form name
        parent::__construct('index');

        //Delete selected button
        $this->add([
            'name'=>'delete_selected',
            'type'=> 'submit',
            'attributes'=>[
                'value'=>'Delete Selected',
                'id'=>'delete_button',
                'class'=> 'btn btn-danger'
            ]
        ]);

        //Add new button
        $this->add([
            'name'=>'add_new',
            'type'=>'submit',
            'attributes'=>[
                'value'=>'Add new',
                'id'=>'add_new',
                'class'=> 'btn btn-success',
                'onclick'=>"window.location.href='/datagrid/add'",
                'form'=>'action=\'\''
            ],


        ]);
    }


}