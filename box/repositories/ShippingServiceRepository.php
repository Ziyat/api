<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace box\repositories;

use box\entities\shop\shipping\ShippingService;

class ShippingServiceRepository
{
    /**
     * @param $id
     * @return ShippingService
     * @throws NotFoundException
     */
    public function get($id): ShippingService
    {
        if(!$shippingService = ShippingService::findOne($id)){
            throw new NotFoundException('Shipping service is not found!');
        }
        return $shippingService;
    }

    /**
     * @param $id
     * @return ShippingService|array
     * @throws NotFoundException
     */
    public function getByRateId($id): ShippingService
    {
        if(!$shippingService = ShippingService::find()->joinWith('shippingServiceRates')->andWhere(['shipping_service_rates.id' => $id])->one()){
            throw new NotFoundException('Shipping service is not found!');
        }
        return $shippingService;
    }

    /**
     * @param ShippingService $shippingService
     * @throws \RuntimeException
     */

    public function save(ShippingService $shippingService)
    {
        if (!$shippingService->save()) {
            throw new \RuntimeException('Saving error.');
        }
    }

    /**
     * @param ShippingService $shippingService
     * @throws \RuntimeException
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */

    public function remove(ShippingService $shippingService)
    {
        if (!$shippingService->delete()) {
            throw new \RuntimeException('Removing error.');
        }
    }
}