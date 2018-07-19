<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace api\controllers\shop;

use api\controllers\BearerController;
use box\entities\shop\Categories;
use box\entities\shop\Category;
use box\forms\Shop\CategoriesForm;
use box\forms\shop\CategoryForm;
use box\services\CategoriesService;
use box\services\CategoryService;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;
use yii\web\BadRequestHttpException;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

class CategoryController extends BearerController
{
    private $categoryService;

    public $action;

    public function __construct(string $id, $module, CategoryService $categoryService, array $config = [])
    {
        $this->categoryService = $categoryService;
        parent::__construct($id, $module, $config);
    }

    /**
     * @SWG\GET(
     *     path="/shop/categories",
     *     tags={"Categories"},
     *     @SWG\Response(
     *         response=200,
     *         description="Success response",
     *     ),
     * )
     * @return ActiveDataProvider
     */

    public function actionIndex()
    {
        return new ActiveDataProvider([
            'query' => Category::find(),
        ]);
    }

    /**
     * @SWG\GET(
     *     path="/shop/categories/{id}",
     *     tags={"Category"},
     *     @SWG\Parameter(name="id", in="path", required=true, type="integer"),
     *     @SWG\Response(
     *         response=200,
     *         description="Success response",
     *     ),
     *     security={{"Bearer": {}}}
     * )
     * @param $id
     * @throws NotFoundHttpException
     * @return Category
     */

    public function actionView($id)
    {
        return $this->findModel($id);
    }

    /**
     * @SWG\POST(
     *     path="/shop/categories",
     *     tags={"Categories"},
     *     @SWG\Parameter(name="name", in="formData", required=true, type="string"),
     *     @SWG\Parameter(name="slug", in="formData", required=true, type="string"),
     *     @SWG\Parameter(name="description", in="formData", required=false, type="string"),
     *     @SWG\Parameter(name="parentId", in="formData", required=false, type="integer"),
     *     @SWG\Response(
     *         response=201,
     *         description="Success response",
     *     ),
     *     security={{"Bearer": {}}}
     * )
     * @throws ForbiddenHttpException
     * @return CategoryForm|Category
     */

    public function actionCreate()
    {
        if (!\Yii::$app->user->can('create')) {
            throw new ForbiddenHttpException('Forbidden');
        }
        $form = new CategoryForm();
        $form->load(\Yii::$app->request->bodyParams, '');
        if ($form->validate()) {
            try {
                $category = $this->categoryService->create($form);
                $response = \Yii::$app->getResponse();
                $response->setStatusCode(201);
                $response->getHeaders()->set('Location', Url::to(['shop/categorys/' . $category->id], true));
                return $category;
            } catch (\DomainException $e) {
                throw $e;
            }
        }
        return $form;
    }

    /**
     * @SWG\POST(
     *     path="/shop/categories/{id}",
     *     tags={"Categories"},
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
     * @return CategoryForm|Category
     */

    public function actionUpdate($id)
    {
        if (!\Yii::$app->user->can('create')) {
            throw new ForbiddenHttpException('Forbidden');
        }
        $category = $this->findModel($id);
        $form = new CategoryForm($category);
        $form->load(\Yii::$app->request->bodyParams, '');
        if ($form->validate()) {
            try {
                $this->categoryService->edit($category->id, $form);
                $response = \Yii::$app->getResponse();
                $response->setStatusCode(202);
                $response->getHeaders()->set('Location', Url::to(['shop/categories/' . $category->id], true));
            } catch (\DomainException $e) {
                throw new BadRequestHttpException($e->getMessage());
            }
        }
        return $form;
    }

    /**
     * @SWG\Delete(
     *     path="/shop/categories/{id}",
     *     tags={"Categories"},
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
            $this->categoryService->remove($id);
            \Yii::$app->getResponse()->setStatusCode(204);
        } catch (\DomainException $e) {
            throw new BadRequestHttpException($e->getMessage(), null, $e);
        }
    }


    /**
     * @param integer $id
     * @return Category the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id): Category
    {
        if (($model = Category::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }

}