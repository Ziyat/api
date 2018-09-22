<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace box\forms\shop\product;


use box\entities\Country;
use box\entities\shop\product\ShippingAssignment;
use box\entities\shop\shipping\ShippingServiceRates;
use yii\base\Model;
use yii\helpers\ArrayHelper;

class ProductShippingForm extends Model
{
    public $rate_id;
    public $free_shipping_type;
    public $price;
    public $countryIds = [];

    public function rules()
    {
        return [
            ['free_shipping_type', 'required'],
            [['free_shipping_type'], 'in',
                'range' => [
                    ShippingAssignment::TYPE_NO_FREE,
                    ShippingAssignment::TYPE_FREE,
                    ShippingAssignment::TYPE_PICKUP
                ]
            ],
            [['rate_id', 'free_shipping_type'], 'integer'],
            [['price'], 'double'],
            ['countryIds', 'each', 'rule' => ['integer']],
            ['countryIds', 'each', 'rule' =>
                [
                    'in',
                    'range' => ArrayHelper::getColumn(Country::find()->select('id')->asArray()->all(), 'id')
                ]
            ],
            ['rate_id', 'exist',
                'skipOnError' => true,
                'targetClass' => ShippingServiceRates::class,
                'targetAttribute' => ['rate_id' => 'id']
            ],
        ];
    }
}