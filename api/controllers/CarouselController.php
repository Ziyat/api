<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace api\controllers;


use box\entities\carousel\Carousel;
use box\forms\carousel\CarouselForm;
use box\readModels\CarouselReadModel;
use box\services\CarouselService;
use yii\web\BadRequestHttpException;
use yii\web\ForbiddenHttpException;

class CarouselController extends BearerCrudController
{
    public $service;
    public $carousels;

    public function __construct(
        string $id,
        $module,
        CarouselService $service,
        CarouselReadModel $carousels,
        array $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->service = $service;
        $this->carousels = $carousels;
    }

    /**
     * @SWG\Get(
     *     path="/carousels",
     *     tags={"Carousels"},
     *     description="Return carousels array",
     *     @SWG\Response(
     *         response=200,
     *         description="Success response",
     *         @SWG\Property(property="characteristics", type="array",
     *         @SWG\Items(ref="#/definitions/CarouselData"))
     *     ),
     * )
     */

    public function actionIndex()
    {
        return $this->carousels->getCarousels();
    }


    /**
     * @SWG\Post(
     *     path="/carousels",
     *     tags={"Carousels"},
     *     @SWG\Parameter(name="title", in="formData", required=true, type="string"),
     *     @SWG\Parameter(name="subTitle", in="formData", required=false, type="string"),
     *     @SWG\Parameter(name="description", in="formData", required=true, type="string"),
     *     @SWG\Parameter(name="text", in="formData", required=true, type="string"),
     *     @SWG\Parameter(name="type", in="formData", required=true, type="integer"),
     *     @SWG\Parameter(name="item_id", in="formData", required=true, type="integer"),
     *     description="Return carousels array",
     *     @SWG\Response(
     *         response=200,
     *         description="Success response",
     *         @SWG\Property(ref="#/definitions/CarouselData")
     *     ),
     * )
     * @throws ForbiddenHttpException|BadRequestHttpException
     */

    public function actionCreate()
    {
        if (!\Yii::$app->user->can('create')) {
            throw new ForbiddenHttpException('Forbidden');
        }
        $form = new CarouselForm();
        $form->load(\Yii::$app->request->bodyParams,'');
        if ($form->validate()) {
            try {
                $carousel = $this->service->create($form);
                \Yii::$app->response->setStatusCode(201);
                return $carousel;
            } catch (\Exception $e) {
                throw new BadRequestHttpException($e->getMessage());
            }
        }
        return $form;
    }

    /**
     * @SWG\Post(
     *     path="/carousels/{id}",
     *     tags={"Carousels"},
     *     description="Return carousels array",
     *     @SWG\Parameter(name="id", in="path", required=true, type="string"),
     *     @SWG\Parameter(name="title", in="formData", required=true, type="string"),
     *     @SWG\Parameter(name="subTitle", in="formData", required=false, type="string"),
     *     @SWG\Parameter(name="description", in="formData", required=true, type="string"),
     *     @SWG\Parameter(name="text", in="formData", required=true, type="string"),
     *     @SWG\Parameter(name="type", in="formData", required=true, type="integer"),
     *     @SWG\Parameter(name="item_id", in="formData", required=true, type="integer"),
     *
     *     @SWG\Response(
     *         response=200,
     *         description="Success response",
     *         @SWG\Property(ref="#/definitions/CarouselData")
     *     ),
     * )
     * @param $id
     * @return Carousel|CarouselForm|null
     * @throws BadRequestHttpException
     * @throws ForbiddenHttpException
     */
    public function actionUpdate($id)
    {
        if (!\Yii::$app->user->can('create')) {
            throw new ForbiddenHttpException('Forbidden');
        }
        $carousel = Carousel::findOne($id);
        $form = new CarouselForm($carousel);
        $form->load(\Yii::$app->request->bodyParams, '');
        if ($form->validate()) {
            try {
                $carousel = $this->service->edit($carousel->id, $form);
                \Yii::$app->response->setStatusCode(202);
                return $carousel;
            } catch (\Exception $e) {
                throw new BadRequestHttpException($e->getMessage());
            }
        }
        return $form;
    }

    /**
     * @SWG\Get(
     *     path="/carousels/{id}",
     *     tags={"Carousels"},
     *     @SWG\Parameter(name="id", in="path", required=true, type="integer"),
     *     description="Return carousels array",
     *     @SWG\Response(
     *         response=200,
     *         description="Success response",
     *         @SWG\Property(ref="#/definitions/CarouselData")
     *     ),
     * )
     *
     * @param $id
     * @return Carousel|null
     */

    public function actionView($id)
    {
        return $this->carousels->getCarousel($id);
    }

    /**
     * @SWG\Delete(
     *     path="/carousels/{id}",
     *     tags={"Carousels"},
     *     @SWG\Parameter(name="id", in="path", required=true, type="integer"),
     *     description="Return carousels array",
     *     @SWG\Response(
     *         response=204,
     *         description="Success response",
     *     ),
     * )
     * @param $id
     * @throws BadRequestHttpException
     * @throws ForbiddenHttpException
     * @throws \Throwable
     * @throws \box\repositories\NotFoundException
     * @throws \yii\db\StaleObjectException
     */

    public function actionDelete($id)
    {
        if (!\Yii::$app->user->can('create')) {
            throw new ForbiddenHttpException('Forbidden');
        }
        try {
            $this->service->remove($id);
            \Yii::$app->getResponse()->setStatusCode(204);
        } catch (\DomainException $e) {
            throw new BadRequestHttpException($e->getMessage(), null, $e);
        }
    }
}

/**
 * @SWG\Definition(
 *     definition="CarouselData",
 *     type="object",
 *     @SWG\Property(property="id", type="integer"),
 *     @SWG\Property(property="title", type="string"),
 *     @SWG\Property(property="description", type="string"),
 *     @SWG\Property(property="text", type="string"),
 *     @SWG\Property(property="type", type="string"),
 *     @SWG\Property(property="item_id", type="integer"),
 *     @SWG\Property(property="item_photo", type="string"),
 *     @SWG\Property(property="photos", type="array",@SWG\Items()),
 * )
 */