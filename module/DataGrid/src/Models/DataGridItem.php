<?php
/**
 * Created by PhpStorm.
 * User: Serhii Shnurenko
 * Date: 11/14/16
 * Time: 3:48 PM
 */

namespace DataGrid\Models;


use DomainException;
use Zend\Filter\StringTrim;
use Zend\Filter\StripTags;
use Zend\Filter\ToInt;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Zend\Validator\StringLength;

class DataGridItem implements InputFilterAwareInterface
{
    public $id;
    public $dish_name;
    public $category;
    public $description;
    public $imageSrc;
    public $price;
    public $options;

    private $inputFilter;

    public function exchangeArray(array $data)
    {
        $this->id =         !empty($data['id']) ? $data['id'] : null;
        $this->dish_name =  !empty($data['dish_name']) ? $data['dish_name'] : null;
        $this->category =   !empty($data['category'])?$data['category']:null;
        $this->description= !empty($data['description'])?$data['description']:null;
        $this->imageSrc =   !empty($data['image_src'])?$data['image_src']:null;
        $this->price =      !empty($data['price'])?$data['price']:null;
        $this->options =    !empty($data['options'])?$data['options']:null;
    }

    public function getArrayCopy(){
        return [
            'id'            =>  $this->id,
            'dish_name'     =>  $this->dish_name,
            'category'      =>  $this->category,
            'description'   =>  $this->description,
            'image_src'     =>  $this->imageSrc,
            'price'         =>  $this->price,
            'options'       =>  $this->options
        ];
    }

    /**
     * Set input filter
     *
     * @param  InputFilterInterface $inputFilter
     * @return InputFilterAwareInterface
     */
    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new DomainException(sprintf(
            '%s does not allow injection of an alternate input filter',
            __CLASS__
        ));
    }

    /**
     * Retrieve input filter
     *
     * @return InputFilterInterface
     */
    public function getInputFilter()
    {
        if($this->inputFilter){
            return $this->inputFilter;
        }

        $inputFilter = new InputFilter();

        $inputFilter->add([
            'name'=>'id',
            'required'=>true,
            'filters'=>[
                ['name'=>ToInt::class],
            ]
        ]);

        $inputFilter->add([
            'name'=>'dish_name',
            'required' => true,
            'filters' => [
                ['name' => StripTags::class],
                ['name' => StringTrim::class],
            ],
            'validators' => [
                [
                    'name' => StringLength::class,
                    'options' => [
                        'encoding' => 'UTF-8',
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
            ],
        ]);
        $inputFilter->add([
            'name'=>'options',
            'required' => false,
            'filters' => [
                ['name' => StripTags::class],
                ['name' => StringTrim::class],
            ],
            'validators' => [
                [
                    'name' => StringLength::class,
                    'options' => [
                        'encoding' => 'UTF-8',
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
            ],
        ]);
        $inputFilter->add([
            'name'=>'category',
            'required'=>true,
            'filters'=>[
                ['name'=>ToInt::class],
            ]
        ]);
        $inputFilter->add([
            'name'=>'description',
            'required' => false,
            'filters' => [
                ['name' => StripTags::class],
                ['name' => StringTrim::class],
            ],
            'validators' => [
                [
                    'name' => StringLength::class,
                    'options' => [
                        'encoding' => 'UTF-8',
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
            ],
        ]);
        $inputFilter->add([
            'name'=>'image_src',
            'required' => true,
            'filters' => [
                ['name' => StripTags::class],
                ['name' => StringTrim::class],
            ],
            'validators' => [
                [
                    'name' => StringLength::class,
                    'options' => [
                        'encoding' => 'UTF-8',
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
            ],
        ]);

        $inputFilter->add([
            'name'=>'price',
            'required' => true,
            'filters' => [
                ['name' => StripTags::class],
                ['name' => StringTrim::class],
            ],
            'validators' => [
                [
                    'name' => StringLength::class,
                    'options' => [
                        'encoding' => 'UTF-8',
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
            ],
        ]);
        return $inputFilter;
    }
}