<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace api\controllers;

use box\forms\SearchForm;
use box\services\search\SearchService;
use Yii;
use yii\rest\Controller;

class SearchController extends Controller
{
    public $service;

    public function __construct(
        string $id,
        $module,
        SearchService $service,
        array $config = []
    )
    {
        $this->service = $service;
        parent::__construct($id, $module, $config);
    }


    /**
     * @SWG\Post(
     *     path="/search/brands",
     *     tags={"ElasticSearch"},
     *     description="returns elasticSearch brands data array",
     *     @SWG\Parameter(name="text", in="formData", required=false, type="string"),
     *     @SWG\Response(
     *         response=200,
     *         description="Success response",
     *         @SWG\Items(ref="#/definitions/BrandsResponseData")
     *     ),
     *     @SWG\Response(
     *         response=422,
     *         description="Data Validation Failed.",
     *         @SWG\Items(ref="#/definitions/DataValidationFailed")
     *     ),
     *     security={{"Bearer": {}}}
     * )
     */

    public function actionBrands()
    {
        $response = null;
        $form = new SearchForm();
        $form->load(Yii::$app->request->bodyParams, '');
        if ($form->validate()) {
            return $this->service->brands($form);
        }
        return $form;
    }

    /**
     * @SWG\Post(
     *     path="/search/generic-products",
     *     tags={"ElasticSearch"},
     *     description="returns elasticSearch generic products data array",
     *     @SWG\Parameter(name="text", in="formData", required=false, type="string"),
     *     @SWG\Response(
     *         response=200,
     *         description="Success response",
     *         @SWG\Items(ref="#/definitions/genericProductsResponseData")
     *     ),
     *     @SWG\Response(
     *         response=422,
     *         description="Data Validation Failed.",
     *         @SWG\Items(ref="#/definitions/DataValidationFailed")
     *     ),
     *     security={{"Bearer": {}}}
     * )
     */

    public function actionGenericProducts()
    {
        $response = null;
        $form = new SearchForm();
        $form->load(Yii::$app->request->bodyParams, '');
        if ($form->validate()) {
            return $this->service->genericProducts($form);
        }
        return $form;
    }

    /**
     * @SWG\Post(
     *     path="/search/users",
     *     tags={"ElasticSearch"},
     *     description="returns elasticSearch users data array",
     *     @SWG\Parameter(name="text", in="formData", required=false, type="string"),
     *     @SWG\Response(
     *         response=200,
     *         description="Success response",
     *         @SWG\Items(ref="#/definitions/UsersResponseData")
     *     ),
     *     @SWG\Response(
     *         response=422,
     *         description="Data Validation Failed.",
     *         @SWG\Items(ref="#/definitions/DataValidationFailed")
     *     ),
     *     security={{"Bearer": {}}}
     * )
     */

    public function actionUsers()
    {
        $response = null;
        $form = new SearchForm();
        $form->load(Yii::$app->request->bodyParams, '');
        if ($form->validate()) {
            return $this->service->users($form);
        }
        return $form;
    }

    /**
     * @SWG\Post(
     *     path="/search/user-products",
     *     tags={"ElasticSearch"},
     *     description="returns elasticSearch user products data array",
     *     @SWG\Parameter(name="text", in="formData", required=false, type="string"),
     *     @SWG\Response(
     *         response=200,
     *         description="Success response",
     *         @SWG\Items(ref="#/definitions/genericProductsResponseData")
     *     ),
     *     @SWG\Response(
     *         response=422,
     *         description="Data Validation Failed.",
     *         @SWG\Items(ref="#/definitions/DataValidationFailed")
     *     ),
     *     security={{"Bearer": {}}}
     * )
     */


    public function actionUserProducts()
    {
        $response = null;
        $form = new SearchForm();
        $form->load(Yii::$app->request->bodyParams, '');
        if ($form->validate()) {
            return $this->service->userProducts($form);
        }
        return $form;
    }

    /**
     * @SWG\Post(
     *     path="/search/combination",
     *     tags={"ElasticSearch"},
     *     description="returns elasticSearch all data array",
     *     @SWG\Parameter(name="text", in="formData", required=false, type="string"),
     *     @SWG\Response(
     *         response=200,
     *         description="Success response",
     *         @SWG\Items(ref="#/definitions/genericProductsResponseData")
     *     ),
     *     @SWG\Response(
     *         response=422,
     *         description="Data Validation Failed.",
     *         @SWG\Items(ref="#/definitions/DataValidationFailed")
     *     ),
     *     security={{"Bearer": {}}}
     * )
     */

    public function actionCombination()
    {
        $response = null;
        $form = new SearchForm();
        $form->load(Yii::$app->request->bodyParams, '');
        if ($form->validate()) {
            return $this->service->combination($form);
        }
        return $form;
    }
}

/**
 * @SWG\Definition(
 *     definition="BrandsResponseData",
 *     description="ElasticSearch result data",
 *     type="object",
 *     @SWG\Property(property="_index", type="string"),
 *     @SWG\Property(property="_type", type="string"),
 *     @SWG\Property(property="_id", type="string"),
 *     @SWG\Property(property="_score", type="integer"),
 *     @SWG\Property(property="_source", type="object",
 *          @SWG\Property(property="name", type="string")
 *     ),
 *
 * )
 */

/**
 * @SWG\Definition(
 *     definition="UsersResponseData",
 *     description="ElasticSearch result data",
 *     type="object",
 *     @SWG\Property(property="_index", type="string"),
 *     @SWG\Property(property="_type", type="string"),
 *     @SWG\Property(property="_id", type="string"),
 *     @SWG\Property(property="_score", type="integer"),
 *     @SWG\Property(property="_source", type="object",
 *          @SWG\Property(property="name", type="string"),
 *          @SWG\Property(property="lastName", type="string"),
 *          @SWG\Property(property="dateOfBirth", type="string"),
 *          @SWG\Property(property="photo", type="string"),
 *     ),
 *
 * )
 */

/**
 * @SWG\Definition(
 *     definition="genericProductsResponseData",
 *     description="ElasticSearch result data",
 *     type="object",
 *     @SWG\Property(property="_index", type="string"),
 *     @SWG\Property(property="_type", type="string"),
 *     @SWG\Property(property="_id", type="string"),
 *     @SWG\Property(property="_score", type="integer"),
 *     @SWG\Property(property="_source", type="object",
 *              @SWG\Property(property="categoryName", type="string"),
 *              @SWG\Property(property="categoryBreadcrumbs", type="string",description="example: parent / parent / mainCategory"),
 *              @SWG\Property(property="name", type="string"),
 *              @SWG\Property(property="categoryId", type="integer"),
 *              @SWG\Property(property="brandId", type="integer"),
 *              @SWG\Property(property="brandName", type="string"),
 *     ),
 *
 * )
 */

/**
 * @SWG\Definition(
 *     definition="DataValidationFailed",
 *     type="object",
 *     @SWG\Property(property="field", type="string"),
 *     @SWG\Property(property="message", type="string"),
 * )
 */