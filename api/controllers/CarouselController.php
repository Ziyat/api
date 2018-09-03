<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace api\controllers;


use box\entities\carousel\Carousel;
use box\entities\carousel\Item;
use box\forms\carousel\CarouselForm;
use box\forms\carousel\ItemForm;
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

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator']['only'] = array_merge(
            $behaviors['authenticator']['only'],
            ['add-item', 'update-item', 'delete-item', 'view-item', 'add-item-images', 'delete-item-image']
        );
        $behaviors['access']['only'] = array_merge(
            $behaviors['access']['only'],
            ['add-item', 'update-item', 'delete-item', 'view-item', 'add-item-images', 'delete-item-image']
        );
        return $behaviors;
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
     * @SWG\Get(
     *     path="/carousels/active",
     *     tags={"Carousels"},
     *     description="Return status active carousels array",
     *     @SWG\Response(
     *         response=200,
     *         description="Success response",
     *         @SWG\Property(property="characteristics", type="array",
     *         @SWG\Items(ref="#/definitions/CarouselData"))
     *     ),
     * )
     */

    public function actionActive()
    {
        return $this->carousels->getCarouselsActive();
    }


    /**
     * @SWG\Post(
     *     path="/carousels",
     *     tags={"Carousels"},
     *     @SWG\Parameter(name="title", in="formData", required=true, type="string"),
     *     @SWG\Parameter(name="subTitle", in="formData", required=false, type="string"),
     *     @SWG\Parameter(name="type", in="formData", required=true, type="integer"),
     *     @SWG\Parameter(name="template_id", in="formData", required=true, type="integer"),
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
     *     @SWG\Parameter(name="title", in="formData", required=true, type="string"),
     *     @SWG\Parameter(name="subTitle", in="formData", required=false, type="string"),
     *     @SWG\Parameter(name="type", in="formData", required=true, type="integer"),
     *     @SWG\Parameter(name="template_id", in="formData", required=true, type="integer"),
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

    /**
     * @SWG\Post(
     *     path="/carousels/{carousel_id}/items",
     *     tags={"Carousels"},
     *     @SWG\Parameter(name="carousel_id", in="path", required=true, type="integer"),
     *     @SWG\Parameter(name="title", in="formData", required=true, type="string"),
     *     @SWG\Parameter(name="item_id", in="formData", required=true, type="integer"),
     *     @SWG\Parameter(name="description", in="formData", required=false, type="string"),
     *     @SWG\Parameter(name="text", in="formData", required=false, type="string"),
     *     @SWG\Parameter(name="files", in="formData", required=false, type="file"),
     *     description="Return carousels array",
     *     @SWG\Response(
     *         response=201,
     *         description="Success response",
     *         @SWG\Property(ref="#/definitions/CarouselData")
     *     ),
     * )
     * @param $carousel_id
     * @return ItemForm|Carousel
     * @throws BadRequestHttpException
     * @throws ForbiddenHttpException
     */
    public function actionAddItem($carousel_id)
    {
        if (!\Yii::$app->user->can('create')) {
            throw new ForbiddenHttpException('Forbidden');
        }

        $form = new ItemForm();
        $form->load(\Yii::$app->request->bodyParams, '');
        if ($form->validate()) {
            try {
                $carousel = $this->service->addItem($carousel_id, $form);
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
     *     path="/carousels/{carousel_id}/items/{item_id}",
     *     tags={"Carousels"},
     *     @SWG\Parameter(name="carousel_id", in="path", required=true, type="integer"),
     *     @SWG\Parameter(name="item_id", in="path", required=true, type="integer"),
     *     @SWG\Parameter(name="title", in="formData", required=false, type="string"),
     *     @SWG\Parameter(name="item_id", in="formData", required=false, type="integer"),
     *     @SWG\Parameter(name="description", in="formData", required=false, type="string"),
     *     @SWG\Parameter(name="text", in="formData", required=false, type="string"),
     *     description="Return carousels array",
     *     @SWG\Response(
     *         response=202,
     *         description="Success response",
     *         @SWG\Property(ref="#/definitions/CarouselData")
     *     ),
     * )
     * @param $carousel_id
     * @param $item_id
     * @return Carousel|ItemForm
     * @throws BadRequestHttpException
     * @throws ForbiddenHttpException
     */

    public function actionUpdateItem($carousel_id, $item_id)
    {
        if (!\Yii::$app->user->can('create')) {
            throw new ForbiddenHttpException('Forbidden');
        }
        $item = Item::findOne($item_id);
        $form = new ItemForm($item);
        $form->load(\Yii::$app->request->bodyParams, '');
        if ($form->validate()) {
            try {
                $carousel = $this->service->editItem($carousel_id, $item_id, $form);
                \Yii::$app->response->setStatusCode(202);
                return $carousel;
            } catch (\Exception $e) {
                throw new BadRequestHttpException($e->getMessage());
            }
        }
        return $form;
    }

    /**
     * @SWG\Delete(
     *     path="/carousels/{carousel_id}/items/{item_id}",
     *     tags={"Carousels"},
     *     @SWG\Parameter(name="carousel_id", in="path", required=true, type="integer"),
     *     @SWG\Parameter(name="item_id", in="path", required=true, type="integer"),
     *     description="Return carousels array",
     *     @SWG\Response(
     *         response=204,
     *         description="Success response"
     *     ),
     * )
     * @param $carousel_id
     * @param $item_id
     * @return Carousel
     * @throws BadRequestHttpException
     * @throws ForbiddenHttpException
     */

    public function actionDeleteItem($carousel_id, $item_id)
    {
        if (!\Yii::$app->user->can('create')) {
            throw new ForbiddenHttpException('Forbidden');
        }

        try {
            $carousel = $this->service->removeItem($carousel_id, $item_id);
            \Yii::$app->response->setStatusCode(204);
            return $carousel;
        } catch (\Exception $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

    }

    /**
     * @SWG\Get(
     *     path="/carousels/{carousel_id}/items/{item_id}",
     *     tags={"Carousels"},
     *     @SWG\Parameter(name="carousel_id", in="path", required=true, type="integer"),
     *     @SWG\Parameter(name="item_id", in="path", required=true, type="integer"),
     *     description="Return carousels array",
     *     @SWG\Response(
     *         response=200,
     *         description="Success response",
     *         @SWG\Property(ref="#/definitions/CarouselItemData"))
     *     ),
     * )
     * @param $carousel_id
     * @param $item_id
     * @return Item
     * @throws BadRequestHttpException
     * @throws ForbiddenHttpException
     */
    public function actionViewItem($carousel_id, $item_id)
    {
        if (!\Yii::$app->user->can('create')) {
            throw new ForbiddenHttpException('Forbidden');
        }
        try {
            $item = $this->carousels->getItemById($carousel_id, $item_id);
            return $item;
        } catch (\Exception $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

    }

    /**
     * @SWG\Get(
     *     path="/carousels/{carousel_id}/items",
     *     tags={"Carousels"},
     *     @SWG\Parameter(name="carousel_id", in="path", required=true, type="integer"),
     *     description="Return carousels array",
     *     @SWG\Response(
     *         response=200,
     *         description="Success response",
     *         @SWG\Property(ref="#/definitions/CarouselItemData")
     *     ),
     * )
     * @param $carousel_id
     * @return \yii\data\ActiveDataProvider
     * @throws BadRequestHttpException
     * @throws ForbiddenHttpException
     */

    public function actionItems($carousel_id)
    {
        if (!\Yii::$app->user->can('create')) {
            throw new ForbiddenHttpException('Forbidden');
        }
        try {
            $items = $this->carousels->getItemsByCarouselId($carousel_id);
            return $items;
        } catch (\Exception $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

    }

    /**
     * @SWG\Post(
     *     path="/carousels/{carousel_id}/items/{item_id}/images",
     *     tags={"Carousels"},
     *     @SWG\Parameter(name="carousel_id", in="path", required=true, type="integer"),
     *     @SWG\Parameter(name="item_id", in="path", required=true, type="integer"),
     *     @SWG\Parameter(name="files", in="formData", required=true, type="file"),
     *     description="Return carousels array",
     *     @SWG\Response(
     *         response=200,
     *         description="Success response",
     *         @SWG\Property(ref="#/definitions/CarouselData"))
     *     ),
     * )
     * @param $carousel_id
     * @param $item_id
     * @return Carousel|ItemForm
     * @throws BadRequestHttpException
     * @throws ForbiddenHttpException
     */

    public function actionAddItemImages($carousel_id, $item_id)
    {
        if (!\Yii::$app->user->can('create')) {
            throw new ForbiddenHttpException('Forbidden');
        }
        $item = Item::findOne($item_id);
        $form = new ItemForm($item);
        $form->load(\Yii::$app->request->bodyParams, '');
        if ($form->validate()) {
            try {
                $carousel = $this->service->addItemImages($carousel_id, $item_id, $form);
                \Yii::$app->response->setStatusCode(201);
                return $carousel;
            } catch (\Exception $e) {
                throw new BadRequestHttpException($e->getMessage());
            }
        }
        return $form;
    }

    /**
     * @SWG\Delete(
     *     path="/carousels/{carousel_id}/items/{item_id}/images/{image_id}",
     *     tags={"Carousels"},
     *     @SWG\Parameter(name="carousel_id", in="path", required=true, type="integer"),
     *     @SWG\Parameter(name="item_id", in="path", required=true, type="integer"),
     *     @SWG\Parameter(name="image_id", in="path", required=true, type="integer"),
     *     description="Return carousels array",
     *     @SWG\Response(
     *         response=200,
     *         description="Success response",
     *         @SWG\Property(ref="#/definitions/CarouselData"))
     *     ),
     * )
     * @param $carousel_id
     * @param $item_id
     * @param $image_id
     * @return bool
     * @throws BadRequestHttpException
     * @throws ForbiddenHttpException
     */
    public function actionDeleteItemImage($carousel_id, $item_id, $image_id)
    {
        if (!\Yii::$app->user->can('create')) {
            throw new ForbiddenHttpException('Forbidden');
        }

        try {
            $this->service->removeItemImages($carousel_id, $item_id, $image_id);
            \Yii::$app->response->setStatusCode(204);
            return true;
        } catch (\Exception $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

    }



}

/**
 * @SWG\Definition(
 *     definition="CarouselData",
 *     type="object",
 *     @SWG\Property(property="id", type="integer"),
 *     @SWG\Property(property="title", type="string"),
 *     @SWG\Property(property="type", type="string"),
 *     @SWG\Property(property="template_id", type="integer"),
 *     @SWG\Property(property="sub_title", type="string"),
 *     @SWG\Property(property="status", type="integer"),
 *     @SWG\Property(property="items", type="array",@SWG\Items(ref="#/definitions/CarouselItemData")),
 * )
 */

/**
 * @SWG\Definition(
 *     definition="CarouselItemData",
 *     type="object",
 *     @SWG\Property(property="id",type="integer"),
 *          @SWG\Property(property="title",type="string"),
 *          @SWG\Property(property="description",type="string"),
 *          @SWG\Property(property="text",type="string"),
 *          @SWG\Property(property="item_id",type="integer"),
 *          @SWG\Property(property="item_img",type="string"),
 *          @SWG\Property(property="images",type="array", @SWG\Items(
 *               @SWG\Property(property="id",type="integer"),
 *               @SWG\Property(property="url",type="string"),
 *          )),
 * )
 */



