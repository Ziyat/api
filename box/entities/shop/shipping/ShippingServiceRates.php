<?php

namespace box\entities\shop\shipping;

use box\entities\Country;
use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * @property integer $id
 * @property integer $shipping_service_id
 * @property integer $price_type
 * @property float $price_min
 * @property float $price_max
 * @property float $price_fix
 * @property integer $day_min
 * @property integer $day_max
 *
 * @property integer $country_id
 * @property integer $type
 *
 * @property float $weight
 *
 * @property ShippingService $shippingService
 * @property ShippingRateDestination $destinations[]
 * @property Country $country
 * @property Country $destinationCountries[]
 */
class ShippingServiceRates extends ActiveRecord
{

    const PRICE_TYPE_FIX = 10;
    const PRICE_TYPE_VARIABLE = 20;

    const TYPE_DOMESTIC = 10;
    const TYPE_INTERNATIONAL = 20;

    public static function create(
        $price_type,
        $price_min,
        $price_max,
        $price_fix,
        $day_min,
        $day_max,
        $country_id,
        $type,
        $weight
    ): self
    {
        $shippingServiceRates = new static();
        $shippingServiceRates->price_type = $price_type;
        $shippingServiceRates->price_min = $price_min;
        $shippingServiceRates->price_max = $price_max;
        $shippingServiceRates->price_fix = $price_fix;
        $shippingServiceRates->day_min = $day_min;
        $shippingServiceRates->day_max = $day_max;
        $shippingServiceRates->country_id = $country_id;
        $shippingServiceRates->type = $type;
        $shippingServiceRates->weight = $weight;
        return $shippingServiceRates;
    }

    public function edit(
        $price_type,
        $price_min,
        $price_max,
        $price_fix,
        $day_min,
        $day_max,
        $country_id,
        $type,
        $weight
    ): void
    {
        $this->price_type = $price_type;
        $this->price_min = $price_min;
        $this->price_max = $price_max;
        $this->price_fix = $price_fix;
        $this->day_min = $day_min;
        $this->day_max = $day_max;
        $this->country_id = $country_id;
        $this->type = $type;
        $this->weight = $weight;
    }

    public function assignDestination($destination_id): void
    {
        $destinations = $this->destinations;
        foreach ($destinations as $k => $destination) {
            /**
             * @var ShippingRateDestination $destination
             */
            if ($destination->isIdEqualTo($destination_id)) {
                return;
            }
        }
        $destinations[] = ShippingRateDestination::create($destination_id);
        $this->destinations = $destinations;

    }

    public function revokeDestinations()
    {
        $this->destinations = [];
    }

    public function getDestinations(): ActiveQuery
    {
        return $this->hasMany(ShippingRateDestination::class, ['rate_id' => 'id']);
    }

    public function getDestinationCountries(): ActiveQuery
    {
        return $this->hasMany(Country::class, ['id' => 'destination_id'])->via('destinations');
    }

    public function isIdEqualTo($id)
    {
        return $this->id == $id;
    }

    public function getCountry()
    {
        return $this->hasOne(Country::class, ['id' => 'country_id']);
    }

    public function getShippingService(): ActiveQuery
    {
        return $this->hasOne(ShippingService::class, ['id' => 'shipping_service_id']);
    }


    public function fields()
    {
        return [
            'id' => 'id',
            'price_type' => function (self $model) {
                return $model->price_type == $model::PRICE_TYPE_FIX
                    ? [
                        'name' => 'price fix',
                        'code' => $model::PRICE_TYPE_FIX,
                    ] : [
                        'name' => 'price variable',
                        'code' => $model::PRICE_TYPE_VARIABLE,
                    ];
            },

            'price_min' => 'price_min',
            'price_max' => 'price_max',
            'price_fix' => 'price_fix',
            'day_min' => 'day_min',
            'day_max' => 'day_max',
            'country' => function (self $model) {
                return [
                    'id' => $model->country->id,
                    'name' => $model->country->name,
                    'code' => $model->country->code,
                ];
            },
            'destinations' => function (self $model) {
                return $model->destinationCountries;
            },
            'type' => function (self $model) {
                return $model->type == $model::TYPE_DOMESTIC
                    ? [
                        'name' => 'domestic',
                        'code' => $model::TYPE_DOMESTIC,
                    ] : [
                        'name' => 'international',
                        'code' => $model::TYPE_INTERNATIONAL,
                    ];
            },
            'weight' => 'weight'
        ];
    }

    public function behaviors(): array
    {
        return [
            [
                'class' => SaveRelationsBehavior::class,
                'relations' => ['destinations']
            ],
        ];
    }

    public static function tableName()
    {
        return '{{%shipping_service_rates}}';
    }
}