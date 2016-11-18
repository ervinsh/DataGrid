<?php

namespace DataGrid\Forms;

use Zend\Form\Form;
use Zend\Form\Element;

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


        $this->add([
            'name'=>'submit',
            'type'=> 'submit',
            'attributes'=>[
                'value'=>'Next',
                'id'=>'submit_button',
                'class'=> 'btn btn-primary'
            ]
        ]);

    }


    /** Adds element to form
     * @param $typeName string holding typename
     * @param $elementName
     * @param $label
     * @param null $options optional parameter used only if typename = category
     */
    public function addElement($typeName, $elementName, $label, $options=null){
        //this is default element array configuration
        $defaultElement = [
            'name'=>$elementName,
            'type'=>'text',
            'options'=>[
                'label' => $label
            ],
            'attributes'=>[
                'class' => 'form-control'
            ]
        ];

        //case type is VARCHAR or TEXT add a like input
        if(strpos(strtolower($typeName),'varchar')!==false||
            strpos(strtolower($typeName),'text')!==false)
        {
            $this->add($defaultElement);
            return;
        }

        //case type is FLOAT or INT add range slider
        if(strpos(strtolower($typeName),'float')!==false||
            strpos(strtolower($typeName),'int')!==false)
        {
            $this->add($defaultElement);
            return;
        }

         /*case type is CATEGORY add checkboxlist
        this is somewhat hack to simplify customization*/
        if(strpos(strtolower($typeName),'category')!==false)
        {
            $defaultElement['type'] = Element\MultiCheckbox::class;

            $defaultElement['options']['value_options']=$options;
            unset($defaultElement['attributes']);
            $this->add($defaultElement);

        }

    }


}