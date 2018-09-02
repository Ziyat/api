<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace api\controllers\shop;

use api\controllers\BearerCrudController;
use box\entities\shop\Brand;
use box\forms\SearchForm;
use box\forms\shop\BrandForm;
use box\readModels\BrandReadModel;
use box\services\BrandService;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\helpers\Url;
use yii\web\BadRequestHttpException;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use Yii;

class BrandController extends BearerCrudController
{
    private $brandService;

    public $action;
    public $readModel;

    public function __construct(
        string $id,
        $module,
        BrandService $brandService,
        BrandReadModel $readModel,
        array $config = []
    )
    {
        $this->brandService = $brandService;
        $this->readModel = $readModel;
        parent::__construct($id, $module, $config);
    }

    /**
     * @SWG\GET(
     *     path="/shop/brands/{brand_id}/users",
     *     tags={"Brand"},
     *     @SWG\Response(
     *         response=200,
     *         description="Success response",
     *         @SWG\Property(property="brands", type="array",@SWG\Items(ref="#/definitions/Brand"))
     *     ),
     * )
     * @param $brand_id
     * @return ActiveDataProvider
     */

    public function actionUsers($brand_id)
    {
        return $this->readModel->getUsers($brand_id);
    }

    /**
     * @SWG\GET(
     *     path="/shop/brands",
     *     tags={"Brand"},
     *     @SWG\Response(
     *         response=200,
     *         description="Success response",
     *         @SWG\Property(property="brands", type="array",@SWG\Items(ref="#/definitions/Brand"))
     *     ),
     * )
     * @return ActiveDataProvider
     */

    public function actionIndex()
    {
        return $this->readModel->getBrands();
    }

    /**
     * @SWG\GET(
     *     path="/shop/brands/{id}",
     *     tags={"Brand"},
     *     @SWG\Parameter(name="id", in="path", required=true, type="integer"),
     *     @SWG\Response(
     *         response=200,
     *         description="Success response",
     *         @SWG\Property(ref="#/definitions/Brand"))
     *     ),
     *     security={{"Bearer": {}}}
     * )
     * @throws NotFoundHttpException
     * @return Brand
     */

    public function actionView($id)
    {
        return $this->readModel->get($id);
    }

    /**
     * @SWG\POST(
     *     path="/shop/brands",
     *     tags={"Brand"},
     *     @SWG\Parameter(name="name", in="formData", required=true, type="string"),
     *     @SWG\Parameter(name="slug", in="formData", required=true, type="string"),
     *     @SWG\Parameter(name="photo", in="formData", required=false, type="file"),
     *     @SWG\Response(
     *         response=201,
     *         description="Success response",
     *         @SWG\Property(ref="#/definitions/Brand"))
     *     ),
     *     security={{"Bearer": {}}}
     * )
     * @throws ForbiddenHttpException
     * @return BrandForm|Brand
     */

    public function actionCreate()
    {
        if (!\Yii::$app->user->can('create')) {
            throw new ForbiddenHttpException('Forbidden');
        }
        $form = new BrandForm();
        $form->load(\Yii::$app->request->bodyParams, '');
        if ($form->validate()) {
            try {
                $brand = $this->brandService->create($form);
                $response = \Yii::$app->getResponse();
                $response->setStatusCode(201);
                $response->getHeaders()->set('Location', Url::to(['shop/brands/' . $brand->id], true));
                return $brand;
            } catch (\DomainException $e) {
                throw $e;
            }
        }
        return $form;
    }

    /**
     * @SWG\POST(
     *     path="/shop/brands/{id}",
     *     tags={"Brand"},
     *     @SWG\Parameter(name="id", in="path", required=true, type="integer"),
     *     @SWG\Parameter(name="name", in="formData", required=true, type="string"),
     *     @SWG\Parameter(name="slug", in="formData", required=true, type="string"),
     *     @SWG\Response(
     *         response=202,
     *         description="Success response",
     *         @SWG\Property(ref="#/definitions/Brand"))
     *     ),
     *     security={{"Bearer": {}}}
     * )
     * @param $id
     * @throws BadRequestHttpException
     * @throws NotFoundHttpException
     * @throws ForbiddenHttpException
     * @return BrandForm|Brand
     */

    public function actionUpdate($id)
    {
        if (!\Yii::$app->user->can('create')) {
            throw new ForbiddenHttpException('Forbidden');
        }
        $brand = $this->readModel->get($id);
        $form = new BrandForm($brand);
        $form->load(\Yii::$app->request->bodyParams, '');
        if ($form->validate()) {
            try {
                $this->brandService->edit($brand->id, $form);
                $response = \Yii::$app->getResponse();
                $response->setStatusCode(202);
                $response->getHeaders()->set('Location', Url::to(['shop/brands/' . $brand->id], true));
            } catch (\DomainException $e) {
                throw new BadRequestHttpException($e->getMessage());
            }
        }
        return $form;
    }

    /**
     * @SWG\Delete(
     *     path="/shop/brands/{id}",
     *     tags={"Brand"},
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
     */

    public function actionDelete($id)
    {
        if (!\Yii::$app->user->can('create')) {
            throw new ForbiddenHttpException('Forbidden');
        }
        try {
            $this->brandService->remove($id);
            \Yii::$app->getResponse()->setStatusCode(204);
        } catch (\DomainException $e) {
            throw new BadRequestHttpException($e->getMessage(), null, $e);
        }
    }

}

/**
 * @SWG\Definition(
 *     definition="Brand",
 *     description="result brand data",
 *     type="object",
 *     @SWG\Property(property="id", type="integer"),
 *     @SWG\Property(property="name", type="string"),
 *     @SWG\Property(property="slug", type="string"),
 *     @SWG\Property(property="photo", type="string"),
 *     @SWG\Property(property="meta", type="object",
 *          @SWG\Property(property="title", type="string"),
 *          @SWG\Property(property="description", type="string"),
 *          @SWG\Property(property="keywords", type="string"),
 *     ),
 *
 * )
 */

/**
 * @SWG\Definition(
 *     definition="SearchBrandsData",
 *     description="ElasticSearch result data",
 *     type="array",
 *     @SWG\Items(
 *          @SWG\Property(property="_index", type="string"),
 *          @SWG\Property(property="_type", type="string"),
 *          @SWG\Property(property="_id", type="string"),
 *          @SWG\Property(property="_score", type="integer"),
 *          @SWG\Property(property="_source", type="object",
 *              @SWG\Property(property="name", type="string"),
 *          ),
 *     ),
 *
 * )
 */