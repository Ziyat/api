<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace box\services;


use box\entities\shop\shipping\ShippingService;
use box\forms\shop\shipping\ShippingServiceForm;
use box\repositories\ShippingServiceRepository;

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
            $shippingService->setRate($rate);
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

        foreach ($form->rates as $rate) {
            $shippingService->setRate($rate);
        }

        $this->shippingServices->save($shippingService);

        return $shippingService;
    }

}