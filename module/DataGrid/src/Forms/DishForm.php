<?php
/**
 * Created by PhpStorm.
 * User: ervinsh
 * Date: 11/16/16
 * Time: 5:38 PM
 */
namespace DataGrid\Forms;

use Zend\Form\Form;

class DishForm extends  Form
{
    public function __construct($name = null)
    {
        //setting form name
        parent::__construct('dish');

        //ID
        $this->add([
            'name' => 'id',
            'type' => 'hidden'
        ]);

        //Dish name
        $this->add([
            'name'=>'dish_name',
            'type'=>'text',
            'options'=>[
                'label' => 'Dish name'
            ],
            'attributes'=>[
                'class' => 'form-control',
                'placeholder'=>'Name'
            ]
        ]);

        //description
        $this->add([
            'name'=>'description',
            'type'=>'textarea',
            'options'=>[
                'label'=>'Description'
            ],
            'attributes'=>[
                'class' => 'form-control',
                'placeholder'=>'Story'
            ]
        ]);

        //price
        $this->add([
            'name'=>'price',
            'type'=>'text',
            'options'=>[
                'label'=>'Price'
            ],
            'attributes'=>[
                'class' => 'form-control',
                'placeholder'=>'0.00'
            ]
        ]);

        //category
        $this->add([
            'name'=>'category',
            'type'=>'text',
            'options'=>[
                'label'=>'Category'
            ],
            'attributes'=>[
                'class' => 'form-control',
                'placeholder'=>'0'
            ]
        ]);

        //options
        $this->add([
            'name'=>'options',
            'type'=>'text',
            'options'=>[
                'label'=>'Options'
            ],
            'attributes'=>[
                'class' => 'form-control',
                'placeholder'=>'option1; option2; option3'
            ]
        ]);

        //img source
        $this->add([
            'name'=>'image_src',
            'type'=>'text',
            'options'=>[
                'label'=>'Image Source'
            ],
            'attributes'=>[
                'class' => 'form-control',
                'placeholder'=>'http://domain.com/img.jpeg'
            ]
        ]);

        //submit button
        $this->add([
            'name'=>'submit',
            'type'=>'submit',
            'attributes'=>[
                'value'=>'Go',
                'id'=>'submitbutton',
                'class'=> 'btn btn-primary'
            ]
        ]);
    }
}