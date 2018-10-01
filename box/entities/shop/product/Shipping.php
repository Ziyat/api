<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace box\entities\shop\product;

use box\entities\behaviors\CountryBehavior;
use box\entities\Country;
use box\entities\shop\shipping\ShippingServiceRates;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * Class Shipping
 * @package box\entities\shop\product
 * @property integer $product_id
 * @property integer $rate_id
 * @property integer $free_shipping_type
 * @property float $price
 * @property string $countries
 * @property array $countryIds
 *
 *
 * @property ShippingServiceRates $rate
 * @property Country $destinations[]
 */
class Shipping extends ActiveRecord
{
    const TYPE_NO_FREE = 0;
    const TYPE_FREE = 1;
    const TYPE_PICKUP = 2;

    public $countryIds;

    public static function create(
        $rate_id,
        $countryIds,
        $free_shipping_type,
        $price
    ): self
    {
        $assignment = new static();
        $assignment->rate_id = $rate_id;
        $assignment->countryIds = $countryIds;
        $assignment->free_shipping_type = $free_shipping_type;
        $assignment->price = $price;
        return $assignment;
    }


    /**
     * @throws \LogicException
     */
    public function pickup()
    {
        if ($this->isNoFree()) {
            throw new \LogicException('free shipping type is already pickup.');
        }

        $this->free_shipping_type = self::TYPE_PICKUP;
    }

    /**
     * @throws \LogicException
     */
    public function noFree()
    {
        if ($this->isNoFree()) {
            throw new \LogicException('free shipping type is already no free.');
        }

        $this->free_shipping_type = self::TYPE_NO_FREE;
    }

    /**
     * @throws \LogicException
     */
    public function free()
    {
        if ($this->isFree()) {
            throw new \LogicException('free shipping type is already free.');
        }

        $this->free_shipping_type = self::TYPE_NO_FREE;
    }

    public function isPickup()
    {
        return $this->free_shipping_type == self::TYPE_PICKUP;
    }

    public function isNoFree()
    {
        return $this->free_shipping_type == self::TYPE_NO_FREE;
    }

    public function isFree()
    {
        return $this->free_shipping_type == self::TYPE_FREE;
    }

    public function isForRateId($id): bool
    {
        return $this->rate_id == $id;
    }


    public function getRate(): ActiveQuery
    {
        return $this->hasOne(ShippingServiceRates::class, ['id' => 'rate_id']);
    }

    public function getDestinations(): ActiveQuery
    {
        return $this->hasMany(Country::class, ['id' => 'countryIds']);
    }


    public function fields()
    {
        return [
            'id' => 'id',
            'product_id' => 'product_id',
            'rate' => function (self $model) {
                return $model->rate_id ? $model->rate : null;
            },
            'destinations' => function (self $model) {
                return isset($model->countryIds) ? $model->destinations : null;
            },
            'price' => 'price',
            'free_shipping_type' => 'free_shipping_type',
        ];
    }

    public function behaviors()
    {
        return [
            CountryBehavior::class,
        ];
    }

    public static function tableName(): string
    {
        return '{{%product_shipping}}';
    }
}