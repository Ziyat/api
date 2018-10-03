<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace box\services;


use box\entities\shop\shipping\ShippingService;
use box\entities\shop\shipping\ShippingServiceRates;
use box\forms\shop\shipping\ShippingServiceForm;
use box\forms\shop\shipping\ShippingServiceRateForm;
use box\repositories\ShippingServiceRepository;

/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 * Class ShippingService
 * @package box\services
 * @property ShippingServiceRepository $shippingServices
 * @property TransactionManager $transaction
 */
class ShippingManageService
{
    public $shippingServices;
    public $transaction;

    public function __construct(ShippingServiceRepository $shippingServiceRepository, TransactionManager $transactionManager)
    {
        $this->shippingServices = $shippingServiceRepository;
        $this->transaction = $transactionManager;
    }

    /**
     * @param ShippingServiceForm $form
     * @return ShippingService
     * @throws \RuntimeException
     */
    public function create(ShippingServiceForm $form)
    {

        $rates = [];
        foreach ($form->rates as $formRate) {

            /**
             * @var ShippingServiceRateForm $formRate
             */

            $rate = ShippingServiceRates::create(
                $formRate->name,
                $formRate->price_type,
                $formRate->price_min,
                $formRate->price_max,
                $formRate->price_fix,
                $formRate->day_min,
                $formRate->day_max,
                $formRate->country_id,
                $formRate->type,
                $formRate->weight,
                $formRate->width,
                $formRate->height,
                $formRate->length
            );

            foreach ($formRate->destinations as $formDestinationId) {
                $rate->assignDestination($formDestinationId);
            }
            $rates[] = $rate;
        }

        $shippingService = ShippingService::create($form->name, $form->description, $form->photo, $rates);

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

        try {
            $this->transaction->wrap(function () use ($shippingService, $form) {
                $rates = [];
                foreach ($form->rates as $formRate) {
                    /**
                     * @var ShippingServiceRateForm $formRate
                     */
                    foreach ($shippingService->shippingServiceRates as $rate) {
                        /**
                         * @var ShippingServiceRates $rate
                         */
                        if ($rate->isIdEqualTo($formRate->id)) {
                            $rate->revokeDestinations();
                            $rate->save();
                            $rate->edit(
                                $formRate->name,
                                $formRate->price_type,
                                $formRate->price_min,
                                $formRate->price_max,
                                $formRate->price_fix,
                                $formRate->day_min,
                                $formRate->day_max,
                                $formRate->country_id,
                                $formRate->type,
                                $formRate->weight,
                                $formRate->width,
                                $formRate->height,
                                $formRate->length
                            );

                            foreach ($formRate->destinations as $formDestinationId) {
                                $rate->assignDestination($formDestinationId);
                            }
                            $rates[] = $rate;
                        }
                    }
                }
                $shippingService->edit($form->name, $form->description, $form->photo, $rates);
                $this->shippingServices->save($shippingService);

            });
        } catch (\Exception $e) {
            throw $e;
        }

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