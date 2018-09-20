<?php

namespace box\entities\shop\shipping;

use box\forms\shop\shipping\ShippingServiceRateForm;
use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\VarDumper;
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

    public static function create($name, $description, $photo): self
    {
        $shippingService = new static();
        $shippingService->name = $name;
        $shippingService->description = $description;
        $shippingService->photo = $photo;
        return $shippingService;
    }

    public function edit($name, $description, $photo): void
    {
        $this->name = $name;
        $this->description = $description;
        $this->photo = $photo;
    }

    public function setRate(ShippingServiceRateForm $form): void
    {
        $rates = $this->shippingServiceRates;
        if($form->id){
            foreach ($rates as $k => $rate) {
                /**
                 * @var ShippingServiceRates $rate
                 */
                if ($rate->isIdEqualTo($form->id)) {
                    $rate->edit(
                        $form->price_type,
                        $form->price_min,
                        $form->price_max,
                        $form->price_fix,
                        $form->day_min,
                        $form->day_max,
                        $form->country_id,
                        $form->type
                    );
                    $rates[$k] = $rate;
                    $this->shippingServiceRates = $rates;
                    return;
                }
            }
        }
        $rates[] = ShippingServiceRates::create(
            $form->price_type,
            $form->price_min,
            $form->price_max,
            $form->price_fix,
            $form->day_min,
            $form->day_max,
            $form->country_id,
            $form->type
        );
        $this->shippingServiceRates = $rates;

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