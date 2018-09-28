<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace box\services;


use box\entities\shop\shipping\ShippingService;
use box\forms\shop\shipping\ShippingServiceForm;
use box\forms\shop\shipping\ShippingServiceRateForm;
use box\repositories\ShippingServiceRepository;
use yii\helpers\VarDumper;

/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 * Class ShippingService
 * @package box\services
 * @property ShippingServiceRepository $shippingServices
 */
class ShippingManageService
{
    public $shippingServices;

    public function __construct(ShippingServiceRepository $shippingServiceRepository)
    {
        $this->shippingServices = $shippingServiceRepository;
    }

    /**
     * @param ShippingServiceForm $form
     * @return ShippingService
     * @throws \RuntimeException
     */
    public function create(ShippingServiceForm $form)
    {
        $shippingService = ShippingService::create($form->name, $form->description, $form->photo);

        foreach ($form->rates as $rate) {
            /**
             * @var ShippingServiceRateForm $rate
             */
            $shippingService->setRate(
                $rate->id,
                $rate->name,
                $rate->price_type,
                $rate->price_min,
                $rate->price_max,
                $rate->price_fix,
                $rate->day_min,
                $rate->day_max,
                $rate->country_id,
                $rate->type,
                $rate->weight,
                $rate->destinations
            );
        }

        $this->shippingServices->save($shippingService);

        return $shippingService;

    }

    /**
     * @param $id
     * @param ShippingServiceForm $form
     * @return ShippingService
     * @throws \RuntimeException
     * @throws \box\repositories\NotFoundException
     */
    public function edit($id, ShippingServiceForm $form)
    {
        $shippingService = $this->shippingServices->get($id);

        $shippingService->edit($form->name, $form->description, $form->photo);
        $shippingService->revokeRates();
        $this->shippingServices->save($shippingService);
        foreach ($form->rates as $rate) {
            /**
             * @var ShippingServiceRateForm $rate
             */
            $shippingService->setRate(
                $rate->id,
                $rate->name,
                $rate->price_type,
                $rate->price_min,
                $rate->price_max,
                $rate->price_fix,
                $rate->day_min,
                $rate->day_max,
                $rate->country_id,
                $rate->type,
                $rate->weight,
                $rate->destinations
            );
        }

        $this->shippingServices->save($shippingService);

        return $shippingService;
    }

    /**
     * @param $id
     * @throws \RuntimeException
     * @throws \Throwable
     * @throws \box\repositories\NotFoundException
     * @throws \yii\db\StaleObjectException
     */
    public function remove($id)
    {
        $shippingService = $this->shippingServices->get($id);
        $this->shippingServices->remove($shippingService);
    }

    /**
     * @param $id
     * @throws \DomainException
     * @throws \RuntimeException
     * @throws \box\repositories\NotFoundException
     */
    public function removeRate($id)
    {
        $shippingService = $this->shippingServices->getByRateId($id);
        $shippingService->unsetRate($id);
        $this->shippingServices->save($shippingService);
    }
}