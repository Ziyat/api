<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace api\controllers\shop;

use api\controllers\BearerCrudController;
use box\forms\shop\shipping\ShippingServiceForm;
use box\readModels\ShippingServiceReadModel;
use box\services\ShippingManageService;
use yii\web\BadRequestHttpException;

/**
 * Class ShippingController
 * @package api\controllers\shop
 * @property ShippingServiceReadModel $shippingServices;
 * @property ShippingManageService $shippingManageService;
 */
class ShippingController extends BearerCrudController
{

    public $shippingManageService;
    public $shippingServices;

    public function __construct(
        string $id,
        $module,
        ShippingManageService $shippingManageService,
        ShippingServiceReadModel $shippingServiceReadModel,
        array $config = []
    )
    {
        $this->shippingManageService = $shippingManageService;
        $this->shippingServices = $shippingServiceReadModel;
        parent::__construct($id, $module, $config);
    }

    /**
     * @SWG\Post(
     *     path="/shop/shipping",
     *     tags={"Shipping"},
     *     @SWG\Parameter(name="name", in="formData", required=true, type="string"),
     *     @SWG\Parameter(name="description", in="formData", required=false, type="string"),
     *     @SWG\Parameter(name="photo", in="formData", required=false, type="file"),
     *     @SWG\Parameter(name="rates", in="body", required=false,
     *         @SWG\Schema(ref="#/definitions/RateForm")
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Success response",
     *     ),
     * )
     * @return \box\entities\shop\shipping\ShippingService|ShippingServiceForm
     * @throws BadRequestHttpException
     * @throws \yii\base\InvalidArgumentException
     */
    public function actionCreate()
    {
        $form = new ShippingServiceForm();
        $form->load(\Yii::$app->request->bodyParams, '');
        if ($form->validate()) {
            try {
                $shippingService = $this->shippingManageService->create($form);
                return $shippingService;
            } catch (\Exception $e) {
                throw new BadRequestHttpException($e->getMessage());
            }
        }
        return $form;
    }

    /**
     * @param $id
     * @return \box\entities\shop\shipping\ShippingService|ShippingServiceForm
     * @throws BadRequestHttpException
     * @throws \box\repositories\NotFoundException
     * @throws \yii\base\InvalidArgumentException
     */
    public function actionUpdate($id)
    {
        $shippingService = $this->shippingServices->get($id);
        $form = new ShippingServiceForm($shippingService);
        $form->load(\Yii::$app->request->bodyParams, '');
        if ($form->validate()) {
            try {
                $shippingService = $this->shippingManageService->edit($shippingService->id, $form);
                return $shippingService;
            } catch (\Exception $e) {
                throw new BadRequestHttpException($e->getMessage());
            }
        }
        return $form;
    }

}
/**
 * @SWG\Definition(
 *     definition="RateForm",
 *     type="array",
 *     @SWG\Items(
 *         @SWG\Property(property="id", type="integer"),
 *         @SWG\Property(property="price_type", type="integer"),
 *         @SWG\Property(property="price_min", type="number"),
 *         @SWG\Property(property="price_max", type="number"),
 *         @SWG\Property(property="day_min", type="integer"),
 *         @SWG\Property(property="day_max", type="integer"),
 *         @SWG\Property(property="country_id", type="integer"),
 *         @SWG\Property(property="type", type="integer"),
 *     )
 * )
 */