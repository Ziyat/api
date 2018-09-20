<?php

namespace box\readModels;

use box\entities\shop\Brand;
use box\entities\shop\shipping\ShippingService;
use box\repositories\NotFoundException;
use Elasticsearch\Client;
use yii\data\ActiveDataProvider;

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
}