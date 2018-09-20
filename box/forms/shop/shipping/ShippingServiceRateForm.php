<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace box\forms\shop\shipping;


use box\entities\Country;
use box\entities\shop\shipping\ShippingServiceRates;
use yii\base\Model;

/**
 * @property integer $id
 * @property integer $price_type
 * @property float $price_min
 * @property float $price_max
 * @property float $price_fix
 * @property integer $day_min
 * @property integer $day_max
 * @property integer $country_id
 * @property integer $type
 */
class ShippingServiceRateForm extends Model
{
    public $id = null;
    public $price_type;
    public $price_min;
    public $price_max;
    public $price_fix;
    public $day_min;
    public $day_max;
    public $country_id;
    public $type;

    public $_rate;

    public function __construct(ShippingServiceRates $rate = null, array $config = [])
    {
        if ($rate) {
            $this->price_type = $rate->price_type;
            $this->price_min = $rate->price_min;
            $this->price_max = $rate->price_max;
            $this->price_fix = $rate->price_fix;
            $this->day_min = $rate->day_min;
            $this->day_max = $rate->day_max;
            $this->country_id = $rate->country_id;
            $this->type = $rate->type;

            $this->_rate = $rate;
        }
        parent::__construct($config);
    }

    public function rules()
    {
        return [
            [['price_type', 'type'], 'required'],
            [['id','price_type', 'day_min', 'day_max', 'type', 'country_id'], 'integer'],
            [['price_min', 'price_max', 'price_fix'], 'number'],
            [['country_id'], 'exist',
                'skipOnError' => true,
                'targetClass' => Country::class,
                'targetAttribute' => ['country_id' => 'id']
            ],
        ];
    }
}