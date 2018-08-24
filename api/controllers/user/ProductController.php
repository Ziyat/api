<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace api\controllers\user;

use api\controllers\BearerController;
use box\entities\generic\GenericProduct;
use box\entities\shop\product\Product;
use box\forms\shop\product\PhotosForm;
use box\forms\shop\product\ProductCreateForm;
use box\forms\shop\product\ProductEditForm;
use box\repositories\NotFoundException;
use box\repositories\ProductRepository;
use box\services\ProductService;
use Yii;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;
use yii\helpers\VarDumper;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

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

    /**
     * @SWG\Get(
     *     path="/user/products",
     *     tags={"User Products"},
     *     description="returns products array",
     *     @SWG\Response(
     *         response=200,
     *         description="Success response",
     *         @SWG\Property(property="characteristics", type="array",
     *         @SWG\Items(ref="#/definitions/ProductData"))
     *
     *     ),
     *     security={{"Bearer": {}}}
     * )
     */

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
     *     @SWG\Parameter(name="genericProductId", in="formData", required=false, type="integer"),
     *     @SWG\Parameter(name="files", in="formData", required=false, type="file"),
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
     *         @SWG\Schema(ref="#/definitions/ProductData")
     *     ),
     *
     *    @SWG\Response(
     *         response=455,
     *         description="Validation error",
     *     ),
     *     security={{"Bearer": {}}}
     * )
     * @return Product|ProductCreateForm
     * @throws BadRequestHttpException|NotFoundException
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
     * @SWG\Post(
     *     path="/user/products/{id}",
     *     tags={"User Products"},
     *     description="edit product",
     *     produces={"application/json"},
     *     @SWG\Parameter(name="name", in="formData", required=true, type="string"),
     *     @SWG\Parameter(name="description", in="formData", required=false, type="string"),
     *     @SWG\Parameter(name="priceType", in="formData", required=true, type="string"),
     *     @SWG\Parameter(name="brandId", in="formData", required=true, type="integer"),
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
     *         @SWG\Schema(ref="#/definitions/ProductData")
     *     ),
     *
     *    @SWG\Response(
     *         response=455,
     *         description="Validation error",
     *     ),
     *     security={{"Bearer": {}}}
     * )
     * @return Product|ProductCreateForm
     * @throws BadRequestHttpException|NotFoundException
     *
     * @param $id
     * @return Product
     * @throws BadRequestHttpException|NotFoundException
     */

    public function actionEdit($id)
    {
        $product = $this->repository->get($id);
        $form = new ProductEditForm($product);
        $form->load(Yii::$app->request->bodyParams, '');
        if ($form->validate()) {
            try {
                $product = $this->service->edit($product->id, $form);
                $response = \Yii::$app->getResponse();
                $response->setStatusCode(202);
                return $product;
            } catch (\DomainException $e) {
                throw new BadRequestHttpException($e->getMessage());
            }
        }
        return $product;
    }

    /**
     * @SWG\Get(
     *     path="/user/products/{id}",
     *     tags={"User Products"},
     *     description="Send the product id",
     *     @SWG\Parameter(name="id", in="path", required=true, type="integer"),
     *     @SWG\Response(
     *         response=200,
     *         description="Success response",
     *         @SWG\Schema(ref="#/definitions/ProductData")
     *     ),
     *     security={{"Bearer": {}}}
     * )
     * @param $id
     * @return Product
     * @throws NotFoundException
     */
    public function actionView($id)
    {
        return $this->repository->get($id);
    }

    /**
     * @SWG\Get(
     *     path="/user/products/{id}/activate",
     *     tags={"User Products"},
     *     description="Send the product id and the product status will become active",
     *     @SWG\Response(
     *         response=200,
     *         description="Success response"
     *     ),
     *     security={{"Bearer": {}}}
     * )
     * @param $id
     * @return boolean
     * @throws NotFoundException
     */
    public function actionActivate($id)
    {
        try {
            $this->service->activate($id);
        } catch (\DomainException $e) {
            return $e->getMessage();
        }
        return true;
    }

    /**
     * @SWG\Get(
     *     path="/user/products/{id}/draft",
     *     tags={"User Products"},
     *     description="Send the product id and the product status will become a draft",
     *     @SWG\Response(
     *         response=200,
     *         description="Success response"
     *     ),
     *     security={{"Bearer": {}}}
     * )
     * @param $id
     * @return boolean
     * @throws NotFoundException
     */
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
     * @SWG\Get(
     *     path="/user/products/{id}/market",
     *     tags={"User Products"},
     *     description="Send the product id and the product status will become a market",
     *     @SWG\Response(
     *         response=200,
     *         description="Success response"
     *     ),
     *     security={{"Bearer": {}}}
     * )
     * @param $id
     * @return boolean
     * @throws NotFoundException
     */
    public function actionMarket($id)
    {
        try {
            $this->service->market($id);
        } catch (\DomainException $e) {
            return $e->getMessage();
        }

        return true;
    }

    /**
     * @SWG\Put(
     *     path="/user/products/{product_id}/{modification_id}/{photo_id}",
     *     tags={"User Products"},
     *     description="Set Modification Photo",
     *     @SWG\Parameter(name="product_id", in="path", required=true, type="integer"),
     *     @SWG\Parameter(name="modification_id", in="path", required=true, type="integer"),
     *     @SWG\Parameter(name="photo_id", in="path", required=true, type="integer"),
     *     @SWG\Response(
     *         response=200,
     *         description="Success response"
     *     ),
     *     security={{"Bearer": {}}}
     * )
     * @param $product_id
     * @param $modification_id
     * @param $photo_id
     * @return bool|string
     * @throws NotFoundException
     */
    public function actionSetModificationPhoto($product_id, $modification_id, $photo_id)
    {
        try {
            $this->service->setModificationPhoto($product_id, $modification_id, $photo_id);
        } catch (\DomainException $e) {
            return $e->getMessage();
        }

        return true;
    }


    /**
     *  @SWG\Delete(
     *     path="/user/products/{product_id}/{modification_id}",
     *     tags={"User Products"},
     *     description="Delete Modification",
     *     @SWG\Parameter(name="product_id", in="path", required=true, type="integer"),
     *     @SWG\Parameter(name="modification_id", in="path", required=true, type="integer"),
     *     @SWG\Response(
     *         response=200,
     *         description="Success response"
     *     ),
     *     security={{"Bearer": {}}}
     * )
     * @param $product_id
     * @param $modification_id
     * @return bool|string
     * @throws NotFoundException
     */
    public function actionDeleteModification($product_id, $modification_id)
    {
        try {
            $this->service->removeModification($product_id, $modification_id);
        } catch (\DomainException $e) {
            return $e->getMessage();
        }

        return true;
    }

    /**
     * @SWG\Post(
     *     path="/user/products/{id}/photos",
     *     tags={"User Products"},
     *     description="added photos",
     *     @SWG\Parameter(name="id", in="path", required=true, type="integer"),
     *     @SWG\Parameter(name="files", in="formData", required=true, type="file"),
     *     @SWG\Response(
     *         response=200,
     *         description="Success response",
     *         @SWG\Schema(ref="#/definitions/ProductData")
     *     ),
     *     security={{"Bearer": {}}}
     * )
     * @param $id
     * @return Product|PhotosForm
     * @throws NotFoundHttpException
     */
    public function actionAddPhotos($id)
    {
        $form = new PhotosForm();
        $form->load(Yii::$app->request->bodyParams, '');
        $form->validate();
        try {
            $product = $this->service->addPhotos($id, $form);
            $response = \Yii::$app->getResponse();
            $response->setStatusCode(202);
            return $product;
        } catch (NotFoundHttpException $e) {
            throw new NotFoundHttpException($e->getMessage());
        }
    }

    /**
     * @SWG\Delete(
     *     path="/user/products/{id}/photos/{photo_id}",
     *     tags={"User Products"},
     *     description="delete photo",
     *     @SWG\Parameter(name="id", in="path", required=true, type="integer"),
     *     @SWG\Parameter(name="photo_id", in="path", required=true, type="integer"),
     *     @SWG\Response(
     *         response=200,
     *         description="Success response"
     *     ),
     *     security={{"Bearer": {}}}
     * )
     * @param integer $id
     * @param $photo_id
     * @return mixed
     * @throws NotFoundException
     */
    public function actionDeletePhoto($id, $photo_id)
    {
        try {
            $this->service->removePhoto($id, $photo_id);
        } catch (\DomainException $e) {
            throw new \DomainException($e->getMessage());
        }
        return true;
    }

    /**
     * @SWG\Patch(
     *     path="/user/products/{id}/photos/{photo_id}/up",
     *     tags={"User Products"},
     *     description="move up photo",
     *     @SWG\Parameter(name="id", in="path", required=true, type="integer"),
     *     @SWG\Parameter(name="photo_id", in="path", required=true, type="integer"),
     *     @SWG\Response(
     *         response=200,
     *         description="Success response"
     *     ),
     *     security={{"Bearer": {}}}
     * )
     * @param integer $id
     * @param $photo_id
     * @return mixed
     * @throws NotFoundException
     */
    public function actionMovePhotoUp($id, $photo_id)
    {
        $this->service->movePhotoUp($id, $photo_id);
        return true;
    }

    /**
     * @SWG\Patch(
     *     path="/user/products/{id}/photos/{photo_id}/down",
     *     tags={"User Products"},
     *     description="move down photo",
     *     @SWG\Parameter(name="id", in="path", required=true, type="integer"),
     *     @SWG\Parameter(name="photo_id", in="path", required=true, type="integer"),
     *     @SWG\Response(
     *         response=200,
     *         description="Success response"
     *     ),
     *     security={{"Bearer": {}}}
     * )
     * @param integer $id
     * @param $photo_id
     * @return mixed
     * @throws NotFoundException
     */
    public function actionMovePhotoDown($id, $photo_id)
    {
        $this->service->movePhotoDown($id, $photo_id);
        return true;
    }

}
/**
 * @SWG\Definition(
 *     definition="ProductData",
 *     type="object",
 *     @SWG\Property(property="id", type="integer"),
 *     @SWG\Property(property="category_id", type="integer"),
 *     @SWG\Property(property="brand_id", type="integer"),
 *     @SWG\Property(property="status", type="integer"),
 *     @SWG\Property(property="name", type="string"),
 *     @SWG\Property(property="description", type="string"),
 *     @SWG\Property(property="quantity", type="integer"),
 *     @SWG\Property(property="photo", type="object",
 *          @SWG\Property(property="id", type="integer"),
 *          @SWG\Property(property="thumb", type="string", description="450X675"),
 *          @SWG\Property(property="large", type="string", description="300X450"),
 *          @SWG\Property(property="search", type="string", description="100X150"),
 *          @SWG\Property(property="original", type="string", description="original"),
 *     ),
 *     @SWG\Property(property="photos", type="array",
 *          @SWG\Items(
 *                  @SWG\Property(property="id", type="integer"),
 *                  @SWG\Property(property="thumb", type="string", description="450X675"),
 *                  @SWG\Property(property="large", type="string", description="300X450"),
 *                  @SWG\Property(property="search", type="string", description="100X150"),
 *                  @SWG\Property(property="original", type="string", description="original"),
 *              )
 *
 *     ),
 *     @SWG\Property(property="price", type="object",
 *         @SWG\Property(property="current", type="object",
 *              @SWG\Property(property="price", type="number"),
 *              @SWG\Property(property="max", type="number"),
 *              @SWG\Property(property="end", type="number"),
 *              @SWG\Property(property="deadline", type="integer"),
 *         ),
 *         @SWG\Property(property="old", type="array",
 *              @SWG\Items(
 *                  @SWG\Property(property="price", type="number"),
 *                  @SWG\Property(property="max", type="number"),
 *                  @SWG\Property(property="end", type="number"),
 *                  @SWG\Property(property="deadline", type="integer"),
 *              )
 *         ),
 *     ),
 *     @SWG\Property(property="characteristics", type="array",
 *          @SWG\Items(
 *              @SWG\Property(property="id", type="integer"),
 *              @SWG\Property(property="name", type="string"),
 *              @SWG\Property(property="value", type="string"),
 *          )
 *     ),
 *     @SWG\Property(property="modifications", type="array",
 *          @SWG\Items(
 *              @SWG\Property(property="id", type="integer"),
 *              @SWG\Property(property="characteristic_id", type="integer"),
 *              @SWG\Property(property="characteristic", type="string"),
 *              @SWG\Property(property="value", type="string"),
 *              @SWG\Property(property="price", type="integer"),
 *              @SWG\Property(property="quantity", type="integer"),
 *              @SWG\Property(property="main_photo_id", type="integer"),
 *              @SWG\Property(property="photo", type="string"),
 *          )
 *     ),
 *     @SWG\Property(property="tags", type="array",
 *          @SWG\Items(
 *              @SWG\Property(property="id", type="integer"),
 *              @SWG\Property(property="name", type="string"),
 *              @SWG\Property(property="slug", type="string")
 *          )
 *     ),
 *     @SWG\Property(property="price_type", type="string"),
 *     @SWG\Property(property="rating", type="number"),
 *     @SWG\Property(property="meta_json", type="object",
 *          @SWG\Property(property="title", type="string"),
 *          @SWG\Property(property="description", type="string"),
 *          @SWG\Property(property="keywords", type="integer"),
 *     ),
 *     @SWG\Property(property="created_at", type="integer"),
 *     @SWG\Property(property="updated_at", type="integer"),
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
 *     @SWG\Property(property="others", type="array",@SWG\Items()),
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