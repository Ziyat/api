<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace api\controllers\shop;

use api\controllers\BearerCrudController;
use box\forms\shop\shipping\ShippingServiceForm;
use box\readModels\ShippingServiceReadModel;
use box\repositories\NotFoundException;
use box\services\ShippingManageService;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\BadRequestHttpException;
use yii\web\ForbiddenHttpException;
use yii\web\Response;

/**
 * Class ShippingController
 * @package api\controllers\shop
 * @property ShippingServiceReadModel $readModel;
 * @property ShippingManageService $manageService;
 * @property Response $response;
 */
class ShippingController extends BearerCrudController
{

    public $manageService;
    public $readModel;
    public $response;

    public function __construct(
        string $id,
        $module,
        ShippingManageService $shippingManageService,
        ShippingServiceReadModel $shippingServiceReadModel,
        array $config = []
    )
    {
        $this->manageService = $shippingManageService;
        $this->readModel = $shippingServiceReadModel;
        $this->response = Yii::$app->getResponse();
        parent::__construct($id, $module, $config);
    }

    /**
     * @SWG\Get(
     *     path="/shop/shipping",
     *     tags={"Shipping"},
     *     @SWG\Response(
     *         response=200,
     *         description="Success response",
     *     ),
     *     security={{"Bearer": {}}}
     * )
     * @return ActiveDataProvider
     */
    public function actionIndex()
    {
        return $this->readModel->getServices();
    }

    /**
     * @SWG\Get(
     *     path="/shop/shipping/{id}",
     *     tags={"Shipping"},
     *     @SWG\Parameter(name="id", in="path", required=true, type="integer"),
     *     @SWG\Response(
     *         response=200,
     *         description="Success response",
     *     ),
     *     security={{"Bearer": {}}}
     * )
     * @param $id
     * @return \box\entities\shop\shipping\ShippingService
     * @throws \box\repositories\NotFoundException
     */
    public function actionView($id)
    {
        return $this->readModel->get($id);
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
     *     security={{"Bearer": {}}}
     * )
     * @return \box\entities\shop\shipping\ShippingService|ShippingServiceForm
     * @throws BadRequestHttpException
     * @throws \yii\base\InvalidArgumentException|ForbiddenHttpException
     */
    public function actionCreate()
    {
        if (!\Yii::$app->user->can('create')) {
            throw new ForbiddenHttpException('Forbidden');
        }
        $form = new ShippingServiceForm();
        $form->load(\Yii::$app->request->bodyParams, '');
        if ($form->validate()) {
            try {
                $shippingService = $this->manageService->create($form);
                $this->response->setStatusCode(201);
                return $shippingService;
            } catch (\Exception $e) {
                throw new BadRequestHttpException($e->getMessage());
            }
        }
        return $form;
    }

    /**
     * @SWG\Post(
     *     path="/shop/shipping/{id}",
     *     tags={"Shipping"},
     *     @SWG\Parameter(name="id", in="path", required=true, type="integer"),
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
     *     security={{"Bearer": {}}}
     * )
     * @param $id
     * @return \box\entities\shop\shipping\ShippingService|ShippingServiceForm
     * @throws BadRequestHttpException
     * @throws \box\repositories\NotFoundException
     * @throws \yii\base\InvalidArgumentException|ForbiddenHttpException
     */
    public function actionUpdate($id)
    {
        if (!\Yii::$app->user->can('create')) {
            throw new ForbiddenHttpException('Forbidden');
        }
        $shippingService = $this->readModel->get($id);
        $form = new ShippingServiceForm($shippingService);
        $form->load(\Yii::$app->request->bodyParams, '');
        if ($form->validate()) {
            try {
                $shippingService = $this->manageService->edit($shippingService->id, $form);
                $this->response->setStatusCode(202);
                return $shippingService;
            } catch (\Exception $e) {
                throw new BadRequestHttpException($e->getMessage());
            }
        }
        return $form;
    }

    /**
     * @SWG\Delete(
     *     path="/shop/shipping/{id}",
     *     tags={"Shipping"},
     *     @SWG\Parameter(name="id", in="path", required=true, type="integer"),
     *     @SWG\Response(
     *         response=200,
     *         description="Success response",
     *     ),
     *     security={{"Bearer": {}}}
     * )
     * @param $id
     * @throws BadRequestHttpException
     * @throws \Throwable
     */
    public function actionDelete($id)
    {
        if (!\Yii::$app->user->can('create')) {
            throw new ForbiddenHttpException('Forbidden');
        }
        try {
            $this->manageService->remove($id);
            $this->response->setStatusCode(204);
        } catch (\Exception $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
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