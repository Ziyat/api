<?php

namespace box\entities\shop\shipping;

use box\forms\shop\shipping\ShippingServiceForm;
use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yiidreamteam\upload\ImageUploadBehavior;

/**
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property string $photo
 *
 * @property ShippingServiceRates $shippingServiceRates[]
 *
 * @mixin ImageUploadBehavior
 */
class ShippingService extends ActiveRecord
{

    public static function create($name, $description, $photo, $rates): self
    {
        $shippingService = new static();
        $shippingService->name = $name;
        $shippingService->description = $description;
        $shippingService->photo = $photo;
        $shippingService->shippingServiceRates = $rates;
        return $shippingService;
    }

    public function edit($name, $description, $photo, $rates): void
    {
        $this->name = $name;
        $this->description = $description;
        $this->photo = $photo;
        $this->shippingServiceRates = $rates;
    }



    /**
     * @param $id
     * @throws \DomainException
     */
    public function unsetRate($id)
    {
        $rates = $this->shippingServiceRates;
        foreach ($rates as $k => $rate) {
            /**
             * @var ShippingServiceRates $rate
             */
            if ($rate->isIdEqualTo($id)) {
                unset($rates[$k]);
                $this->shippingServiceRates = $rates;
                return;
            }
        }

        throw new \DomainException('Rate is not found!');
    }


    public function getShippingServiceRates(): ActiveQuery
    {
        return $this->hasMany(ShippingServiceRates::class, ['shipping_service_id' => 'id']);
    }


    public static function tableName()
    {
        return '{{%shipping_services}}';
    }

    public function behaviors(): array
    {
        return [
            [
                'class' => ImageUploadBehavior::class,
                'attribute' => 'photo',
                'thumbs' => [
                    'admin' => ['width' => 120, 'height' => 120],
                    'thumb' => ['width' => 600, 'height' => 600],
                ],
                'filePath' => '@staticPath/store/shipping_service/[[id]]/[[id]].[[extension]]',
                'fileUrl' => '@staticUrl/store/shipping_service/[[id]]/[[id]].[[extension]]',
                'thumbPath' => '@staticPath/cache/shipping_service/[[id]]/[[profile]]_[[id]].[[extension]]',
                'thumbUrl' => '@staticUrl/cache/shipping_service/[[id]]/[[profile]]_[[id]].[[extension]]',
            ],
            [
                'class' => SaveRelationsBehavior::class,
                'relations' => ['shippingServiceRates']
            ],
        ];
    }

    public function fields()
    {
        return [
            'id' => 'id',
            'name' => 'name',
            'description' => 'description',
            'photo' => function (self $model) {
                return $model->getPhoto();
            },
            'rates' => function (self $model) {
                return $model->shippingServiceRates;
            },
        ];
    }


    /**
     * @param string $profile
     * @return null|string
     * @throws \yii\base\InvalidArgumentException
     */
    public function getPhoto($profile = 'thumb')
    {
        return $this->getThumbFileUrl('photo', $profile, \Yii::getAlias('@staticUrl') . '/empty/no-photo.jpg');
    }
}