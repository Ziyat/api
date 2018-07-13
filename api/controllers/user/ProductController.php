<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace api\controllers\user;

use api\controllers\BearerController;
use box\forms\shop\product\ProductCreateForm;
use box\services\ProductService;
use Yii;
use yii\helpers\VarDumper;

class ProductController extends BearerController
{
    public $productService;

    public function __construct(string $id, $module, ProductService $productService, array $config = [])
    {
        parent::__construct($id, $module, $config);

        $this->productService = $productService;
    }

    public function actionIndex()
    {

        return ['index'];
    }

    /**
     * @SWG\Post(
     *     path="/user/products",
     *     tags={"User Products"},
     *     @SWG\Parameter(name="name", in="formData", required=true, type="string"),
     *     @SWG\Parameter(name="description", in="formData", required=false, type="string"),
     *     @SWG\Parameter(name="priceType", in="formData", required=true, type="string"),
     *     @SWG\Parameter(name="brandId", in="formData", required=true, type="integer"),
     *     @SWG\Parameter(name="categories", in="body", required=true,
     *          @SWG\Schema(type="object",
     *              @SWG\Property(property="main", type="integer"),
     *              @SWG\Property(property="others", type="array",
     *                  @SWG\Items()
     *              ),
     *          )
     *     ),
     *     @SWG\Parameter(name="values", in="body", required=false,
     *          @SWG\Schema(type="array",
     *              @SWG\Items(@SWG\Property(property="value", type="string"))
     *          )
     *     ),
     *     @SWG\Parameter(name="tags", in="body", required=false,
     *          @SWG\Schema(type="object",
     *                  @SWG\Property(property="existing", type="array", @SWG\Items()),
     *                  @SWG\Property(property="textNew", type="string"),
     *          )
     *     ),
     *     @SWG\Parameter(name="meta", in="body", required=false,
     *          @SWG\Schema(type="object",
     *                  @SWG\Property(property="title", type="string"),
     *                  @SWG\Property(property="description", type="string"),
     *                  @SWG\Property(property="keywords", type="string"),
     *          )
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Success response",
     *         @SWG\Schema(
     *             type="array",
     *             @SWG\Items(ref="#/definitions/ProductData")
     *         ),
     *     ),
     *     security={{"Bearer": {}}}
     * )
     */


    public function actionCreate()
    {
        $form = new ProductCreateForm();
        $form->load(Yii::$app->request->bodyParams, '');
        if ($form->validate()) {
            try {
                $product = $this->productService->create($form);
                return $product;
            } catch (\Exception $e) {
                return $e->getMessage();
            }
        }
        return $form->categories;
    }

    public function actionEdit($id)
    {
        return ['edit id: ' . $id, 'params' => \Yii::$app->request->bodyParams];
    }

    public function actionActivate($id)
    {
        try {
            $this->productService->activate($id);
        } catch (\DomainException $e) {
            return $e->getMessage();
        }
        return true;
    }

    public function actionDraft($id)
    {
        try {
            $this->productService->draft($id);
        } catch (\DomainException $e) {
            return $e->getMessage();
        }

        return true;
    }


    /**
     * @SWG\Definition(
     *     definition="ProductData",
     *     type="object",
     *     @SWG\Property(property="name", type="string")
     * )
     */

}