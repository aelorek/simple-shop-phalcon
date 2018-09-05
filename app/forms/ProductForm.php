<?php

namespace App\Forms;

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Numericality;
use Phalcon\Validation\Validator\StringLength;

class ProductForm extends Form
{
    /**
     * @param null  $entity
     * @param array $options
     */
    public function initialize($entity = null, $options = [])
    {
        $name = new Text('name');
        $name->setLabel('Name');
        $name->setFilters(['striptags', 'string']);
        $name->addValidators(
            [
                new PresenceOf(
                    [
                        'message' => 'Nazwa jest wymagana',
                    ]
                ),
            ]
        );
        $this->add($name);

        $description = new Text('description');
        $description->setLabel('Opis');
        $description->setFilters(['striptags', 'string']);
        $description->addValidators(
            [
                new PresenceOf(
                    [
                        'message' => 'Opis jest wymagany',
                    ]
                ),
                new StringLength(
                    [
                        'messageMinimum' => 'Minimalna długość to 100 znaków',
                        'min'            => 100,
                    ]
                ),
            ]
        );
        $this->add($description);

        $price = new Text('price');
        $price->setLabel('Price');
        $price->setFilters(['float']);
        $price->addValidators(
            [
                new PresenceOf(
                    [
                        'message' => 'Cena jest wymagana',
                    ]
                ),
                new Numericality(
                    [
                        'message' => 'Cena jest wymagana',
                    ]
                ),
            ]
        );
        $this->add($price);
    }
}