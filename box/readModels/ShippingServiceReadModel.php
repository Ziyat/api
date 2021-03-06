<?php

namespace box\readModels;

use box\entities\shop\shipping\ShippingService;
use box\forms\shop\shipping\SearchRatesForm;
use box\repositories\NotFoundException;
use yii\data\ActiveDataProvider;
use yii\helpers\VarDumper;
use yii\web\User;

class ShippingServiceReadModel
{

    /**
     * @param $id
     * @return ShippingService
     * @throws NotFoundException
     */
    public function get($id): ShippingService
    {
        if (!$shippingService = ShippingService::findOne($id)) {
            throw new NotFoundException('Shipping service is not found.');
        }
        return $shippingService;
    }

    public function getServices(): ActiveDataProvider
    {
        return new ActiveDataProvider([
            'query' => ShippingService::find()
        ]);
    }


}