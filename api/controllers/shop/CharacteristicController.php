<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace api\controllers\shop;

use api\controllers\BearerController;
use api\controllers\BearerCrudController;
use box\entities\shop\Characteristics;
use box\entities\shop\Characteristic;
use box\forms\Shop\CharacteristicsForm;
use box\forms\shop\CharacteristicForm;
use box\readModels\CharacteristicReadModel;
use box\services\CharacteristicsService;
use box\services\CharacteristicService;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;
use yii\helpers\VarDumper;
use yii\web\BadRequestHttpException;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

class CharacteristicController extends BearerCrudController
{
    private $characteristicService;
    private $characteristics;

    public $action;

    public function __construct(
        string $id,
        $module,
        CharacteristicService $characteristicService,
        CharacteristicReadModel $characteristics,
        array $config = []
    )
    {
        $this->characteristicService = $characteristicService;
        $this->characteristics = $characteristics;
        parent::__construct($id, $module, $config);
    }

    /**
     * @SWG\GET(
     *     path="/shop/characteristics",
     *     tags={"Characteristics"},
     *     @SWG\Response(
     *         response=200,
     *         description="Success response",
     *         @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref="#/definitions/Characteristic")),
     *     ),
     * )
     * @return ActiveDataProvider
     */

    public function actionIndex()
    {
        return new ActiveDataProvider([
            'query' => Characteristic::find(),
        ]);
    }

    /**
     * @SWG\GET(
     *     path="/shop/characteristics/{id}",
     *     tags={"Characteristics"},
     *     @SWG\Parameter(name="id", in="path", required=true, type="integer"),
     *     @SWG\Response(
     *         response=200,
     *         description="Success response",
     *         @SWG\Property(ref="#/definitions/Characteristic"),
     *     ),
     *     security={{"Bearer": {}}}
     * )
     * @param $id
     * @throws NotFoundHttpException
     * @return Characteristic
     */

    public function actionView($id)
    {
        return $this->characteristics->findById($id);
    }

    /**
     * @SWG\GET(
     *     path="/shop/characteristics/category/{id}",
     *     tags={"Characteristics"},
     *     description="send in path category_id, in response, characteristics list",
     *     @SWG\Parameter(name="id", in="path", required=true, type="integer"),
     *     @SWG\Response(
     *         response=200,
     *         description="Success response",
     *         @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref="#/definitions/Characteristic")),
     *     ),
     *     security={{"Bearer": {}}}
     * )
     * @param $id
     * @throws NotFoundHttpException
     * @return array
     */

    public function actionCategory($id)
    {
        return $this->characteristics->findByCategoryId($id);
    }

    /**
     * @SWG\POST(
     *     path="/shop/characteristics",
     *     tags={"Characteristics"},
     *     @SWG\Parameter(name="name", in="formData", required=true, type="string"),
     *     @SWG\Parameter(name="assignments", in="body", required=true,
     *          @SWG\Schema(ref="#/definitions/CharacteristicsAssignments")
     *     ),
     *     @SWG\Response(
     *         response=201,
     *         description="Success response",
     *     ),
     *     security={{"Bearer": {}}}
     * )
     * @throws ForbiddenHttpException
     * @return CharacteristicForm|Characteristic
     */

    public function actionCreate()
    {
        if (!\Yii::$app->user->can('create')) {
            throw new ForbiddenHttpException('Forbidden');
        }
        $form = new CharacteristicForm();
        $form->load(\Yii::$app->request->bodyParams, '');
        if ($form->validate()) {
            try {
                $characteristic = $this->characteristicService->create($form);
                $response = \Yii::$app->getResponse();
                $response->setStatusCode(201);
                $response->getHeaders()->set('Location', Url::to(['shop/characteristics/' . $characteristic->id], true));
                return $characteristic;
            } catch (\DomainException $e) {
                throw $e;
            }
        }
        return $form;
    }

    /**
     * @SWG\POST(
     *     path="/shop/characteristics/{id}",
     *     tags={"Characteristics"},
     *     @SWG\Parameter(name="id", in="path", required=true, type="integer"),
     *     @SWG\Response(
     *         response=202,
     *         description="Success response",
     *     ),
     *     security={{"Bearer": {}}}
     * )
     * @param $id
     * @throws BadRequestHttpException
     * @throws NotFoundHttpException
     * @throws ForbiddenHttpException
     * @return CharacteristicForm|Characteristic
     */

    public function actionUpdate($id)
    {
        if (!\Yii::$app->user->can('create')) {
            throw new ForbiddenHttpException('Forbidden');
        }
        $characteristic = $this->findModel($id);
        $form = new CharacteristicForm($characteristic);
        $form->load(\Yii::$app->request->bodyParams, '');
        if ($form->validate()) {
            try {
                $this->characteristicService->edit($characteristic->id, $form);
                $response = \Yii::$app->getResponse();
                $response->setStatusCode(202);
                $response->getHeaders()->set('Location', Url::to(['shop/characteristics/' . $characteristic->id], true));
            } catch (\DomainException $e) {
                throw new BadRequestHttpException($e->getMessage());
            }
        }
        return $form;
    }

    /**
     * @SWG\Delete(
     *     path="/shop/characteristics/{id}",
     *     tags={"Characteristics"},
     *     @SWG\Parameter(name="id", in="path", required=true, type="integer"),
     *     @SWG\Response(
     *         response=204,
     *         description="Success response",
     *     ),
     *     security={{"Bearer": {}}}
     * )
     * @param $id
     * @throws BadRequestHttpException
     * @throws ForbiddenHttpException
     * @throws \Throwable
     */

    public function actionDelete($id)
    {
        if (!\Yii::$app->user->can('create')) {
            throw new ForbiddenHttpException('Forbidden');
        }
        try {
            $this->characteristicService->remove($id);
            \Yii::$app->getResponse()->setStatusCode(204);
        } catch (\DomainException $e) {
            throw new BadRequestHttpException($e->getMessage(), null, $e);
        }
    }


    /**
     * @param integer $id
     * @return Characteristic the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id): Characteristic
    {
        if (($model = Characteristic::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }

}
/**
 * @SWG\Definition(
 *     definition="Characteristic",
 *     type="object",
 *     @SWG\Property(property="id", type="integer"),
 *     @SWG\Property(property="name", type="string"),
 *     @SWG\Property(property="variants", type="array", @SWG\Items()),
 *     @SWG\Property(property="categories", type="array",
 *          @SWG\Items(ref="#/definitions/Category")
 *     )
 * )
 */

/**
 * @SWG\Definition(
 *     definition="Category",
 *     type="object",
 *     @SWG\Property(property="id",type="integer"),
 *     @SWG\Property(property="name", type="string"),
 *     @SWG\Property(property="slug", type="string"),
 *     @SWG\Property(property="title", type="string"),
 *     @SWG\Property(property="description", type="string"),
 *     @SWG\Property(property="meta_json", type="string"),
 *     @SWG\Property(property="lft", type="integer"),
 *     @SWG\Property(property="rgt", type="integer"),
 *     @SWG\Property(property="depth", type="integer"),
 * )
 */


/**
 * @SWG\Definition(
 *     definition="CharacteristicsAssignments",
 *     type="array",
 *     @SWG\Items(
 *         @SWG\Property(property="category_id",type="integer"),
 *         @SWG\Property(property="variants", type="array",@SWG\Items()),
 *     )
 * )
 */