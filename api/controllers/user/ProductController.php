<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace api\controllers\user;

use api\controllers\BearerController;
use box\entities\shop\Brand;
use box\entities\shop\product\Product;
use box\forms\shop\product\ProductCreateForm;
use box\forms\shop\product\ProductEditForm;
use box\repositories\ProductRepository;
use box\services\ProductService;
use Yii;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;
use yii\helpers\VarDumper;
use yii\web\BadRequestHttpException;

/**
 * Class ProductController
 * @package api\controllers\user
 * @property ProductService $service
 * @property ProductRepository $repository
 */
class ProductController extends BearerController
{
    public $service;
    public $repository;

    public function __construct(
        string $id,
        $module,
        ProductService $service,
        ProductRepository $repository,
        array $config = []
    )
    {
        parent::__construct($id, $module, $config);

        $this->service = $service;
        $this->repository = $repository;
    }

    public function actionIndex()
    {
        return new ActiveDataProvider([
            'query' => Product::find()->where(['created_by' => Yii::$app->user->id]),
        ]);
    }

    /**
     * @SWG\Post(
     *     path="/user/products",
     *     tags={"User Products"},
     *     description="Multipart/form-data",
     *     produces={"application/json"},
     *     consumes={"multipart/form-data"},
     *     @SWG\Parameter(name="name", in="formData", required=true, type="string"),
     *     @SWG\Parameter(name="description", in="formData", required=false, type="string"),
     *     @SWG\Parameter(name="priceType", in="formData", required=true, type="string"),
     *     @SWG\Parameter(name="brandId", in="formData", required=true, type="integer"),
     *     @SWG\Parameter(name="files", in="formData", required=true, type="file"),
     *     @SWG\Parameter(name="categories", in="body", required=true,
     *          @SWG\Schema(ref="#/definitions/CategoriesForm")
     *     ),
     *     @SWG\Parameter(name="price", in="body", required=true,
     *          @SWG\Schema(ref="#/definitions/PriceForm")
     *     ),
     *     @SWG\Parameter(name="characteristics", in="body", required=false,
     *          @SWG\Schema(ref="#/definitions/CharacteristicsForm")
     *     ),
     *     @SWG\Parameter(name="modifications", in="body", required=false,
     *          @SWG\Schema(ref="#/definitions/ModificationsForm")
     *     ),
     *     @SWG\Parameter(name="tags", in="body", required=false,
     *          @SWG\Schema(ref="#/definitions/TagsForm")
     *     ),
     *     @SWG\Parameter(name="meta", in="body", required=false,
     *          @SWG\Schema(ref="#/definitions/MetaForm")
     *     ),
     *     @SWG\Response(
     *         response=201,
     *         description="Created success response",
     *         @SWG\Schema(
     *             type="array",
     *             @SWG\Items(ref="#/definitions/ProductData")
     *         ),
     *     ),
     *
     *    @SWG\Response(
     *         response=455,
     *         description="Validation error",
     *     ),
     *     security={{"Bearer": {}}}
     * )
     * @return Product|ProductCreateForm
     * @throws BadRequestHttpException
     */


    public function actionCreate()
    {
        $form = new ProductCreateForm();
        $form->load(Yii::$app->request->bodyParams, '');
        if ($form->validate()) {
            try {
                $product = $this->service->create($form);
                $response = \Yii::$app->getResponse();
                $response->setStatusCode(201);
                $response->getHeaders()->set('Location', Url::to(['user/products/' . $product->id], true));
                return $product;
            } catch (\DomainException $e) {
                throw new BadRequestHttpException($e->getMessage());
            }
        }
        return $form;
    }

    /**
     * @param $id
     * @return Product
     * @throws BadRequestHttpException
     */

    public function actionEdit($id)
    {
        $product = $this->repository->get($id);
        $form = new ProductEditForm($product);
        $form->load(Yii::$app->request->bodyParams, '');
        if ($form->validate()) {
            try {
                $product = $this->service->edit($product->id,$form);
                $response = \Yii::$app->getResponse();
                $response->setStatusCode(202);
                return $product;
            } catch (\DomainException $e) {
                throw new BadRequestHttpException($e->getMessage());
            }
        }
        return $product;
    }

    public function actionActivate($id)
    {
        try {
            $this->service->activate($id);
        } catch (\DomainException $e) {
            return $e->getMessage();
        }
        return true;
    }

    public function actionDraft($id)
    {
        try {
            $this->service->draft($id);
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

    /**
     * @SWG\Definition(
     *     definition="TagsForm",
     *     type="object",
     *     @SWG\Property(property="existing", type="array", @SWG\Items()),
     *     @SWG\Property(property="textNew", type="string"),
     * )
     */

    /**
     * @SWG\Definition(
     *     definition="PriceForm",
     *     type="object",
     *     @SWG\Property(property="current", type="number"),
     *     @SWG\Property(property="deadline", type="integer"),
     *     @SWG\Property(property="buyNow", type="integer"),
     * )
     */


    /**
     * @SWG\Definition(
     *     definition="MetaForm",
     *     type="object",
     *     @SWG\Property(property="title", type="string"),
     *     @SWG\Property(property="description", type="string"),
     *     @SWG\Property(property="keywords", type="string"),
     * )
     */

    /**
     * @SWG\Definition(
     *     definition="CategoriesForm",
     *     type="object",
     *     @SWG\Property(property="main", type="integer"),
     *              @SWG\Property(property="others", type="array",@SWG\Items()),
     * )
     */

    /**
     * @SWG\Definition(
     *     definition="CharacteristicsForm",
     *     type="array",
     *     @SWG\Items(
     *         @SWG\Property(property="id", type="integer"),
     *         @SWG\Property(property="value", type="string"),
     *     )
     * )
     */

    /**
     * @SWG\Definition(
     *     definition="ModificationsForm",
     *     type="array",
     *     @SWG\Items(
     *         @SWG\Property(property="characteristic_id", type="integer"),
     *         @SWG\Property(property="value", type="string"),
     *         @SWG\Property(property="quantity", type="integer"),
     *         @SWG\Property(property="price", type="integer"),
     *     )
     * )
     */

}