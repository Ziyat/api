<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace api\controllers\shop;

use api\controllers\BearerController;
use api\controllers\BearerCrudController;
use box\entities\shop\Categories;
use box\entities\shop\Category;
use box\forms\Shop\CategoriesForm;
use box\forms\shop\CategoryForm;
use box\services\CategoriesService;
use box\services\CategoryService;
use yii\base\InvalidParamException;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;
use yii\web\BadRequestHttpException;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

class CategoryController extends BearerCrudController
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
            'query' => Category::find()->where('depth > 0'),
        ]);
    }

    /**
     * @SWG\GET(
     *     path="/shop/categories/{id}",
     *     tags={"Categories"},
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
     * @SWG\GET(
     *     path="/shop/categories/{id}/parent",
     *     tags={"Categories"},
     *     description="return parent this category",
     *     @SWG\Parameter(name="id", in="path", required=true, type="integer"),
     *     @SWG\Response(
     *         response=200,
     *         description="Success response",
     *     ),
     *     @SWG\Response(
     *         response=404,
     *         description="Not Found Category",
     *     ),
     * )
     * @param $id
     * @return array|null|\yii\db\ActiveRecord
     * @throws NotFoundHttpException
     */

    public function actionParent($id)
    {
        $category = $this->findModel($id);

        return $category->parent;
    }

    /**
     * @SWG\GET(
     *     path="/shop/categories/{id}/parents",
     *     tags={"Categories"},
     *     description="return parents this category",
     *     @SWG\Parameter(name="id", in="path", required=true, type="integer"),
     *     @SWG\Response(
     *         response=200,
     *         description="Success response",
     *     ),
     *     @SWG\Response(
     *         response=404,
     *         description="Not Found Category",
     *     ),
     * )
     * @param $id
     * @return array|null|\yii\db\ActiveRecord
     * @throws NotFoundHttpException
     */

    public function actionParents($id)
    {
        $category = $this->findModel($id);

        return $category->getParents()->andWhere(['>', 'depth', 0])->all();
    }

    /**
     * @SWG\GET(
     *     path="/shop/categories/{id}/children",
     *     tags={"Categories"},
     *     description="return children this category",
     *     @SWG\Parameter(name="id", in="path", required=true, type="integer"),
     *     @SWG\Response(
     *         response=200,
     *         description="Success response",
     *     ),
     *     @SWG\Response(
     *         response=404,
     *         description="Not Found Category",
     *     ),
     * )
     * @param $id
     * @return array|null|\yii\db\ActiveRecord
     * @throws NotFoundHttpException
     */

    public function actionChildren($id)
    {
        $category = $this->findModel($id);

        return $category->children;
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
     *     @SWG\Response(
     *         response=500,
     *         description="Forbidden, the user does not have privileges",
     *     ),
     *     security={{"Bearer": {}}}
     * )
     * @throws ForbiddenHttpException|InvalidParamException|BadRequestHttpException
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
                throw new BadRequestHttpException($e->getMessage());
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
     *     @SWG\Response(
     *         response=500,
     *         description="Forbidden, the user does not have privileges",
     *     ),
     *     security={{"Bearer": {}}}
     * )
     *
     * @param $id
     * @return CategoryForm
     * @throws BadRequestHttpException
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     * @throws \yii\base\InvalidArgumentException
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
     *     @SWG\Response(
     *         response=500,
     *         description="Forbidden, the user does not have privileges",
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