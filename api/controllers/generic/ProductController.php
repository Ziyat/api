<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace api\controllers\generic;

use api\controllers\BearerCrudController;
use box\entities\generic\GenericProduct;
use box\forms\generic\PhotosForm;
use box\forms\generic\ProductCreateForm;
use box\forms\generic\ProductEditForm;
use box\forms\generic\RatingsForm;
use box\readModels\GenericProductReadRepository;
use box\repositories\generic\ProductRepository;
use box\repositories\NotFoundException;
use box\services\generic\ProductService;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\BadRequestHttpException;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

/**
 * Class ProductController
 * @package api\controllers\user
 * @property ProductService $service
 * @property ProductRepository $repository
 * @property GenericProductReadRepository $readRepository
 */
class ProductController extends BearerCrudController
{
    public $service;
    public $repository;
    public $readRepository;

    public function __construct(
        string $id,
        $module,
        ProductService $service,
        ProductRepository $repository,
        GenericProductReadRepository $readRepository,
        array $config = []
    )
    {
        parent::__construct($id, $module, $config);

        $this->service = $service;
        $this->repository = $repository;
        $this->readRepository = $readRepository;
    }

    /**
     * @SWG\Get(
     *     path="/generic/products",
     *     tags={"Generic Products"},
     *     description="returns products array",
     *     @SWG\Response(
     *         response=200,
     *         description="Success response",
     *         @SWG\Property(property="characteristics", type="array",
     *          @SWG\Items(ref="#/definitions/ProductData"))
     *
     *     ),
     *     security={{"Bearer": {}}}
     * )
     */

    public function actionIndex()
    {
        return new ActiveDataProvider([
            'query' => GenericProduct::find()->where(['created_by' => Yii::$app->user->id]),
        ]);
    }

    /**
     * @SWG\Post(
     *     path="/generic/products",
     *     tags={"Generic Products"},
     *     description="Multipart/form-data",
     *     produces={"application/json"},
     *     consumes={"multipart/form-data"},
     *     @SWG\Parameter(name="name", in="formData", required=true, type="string"),
     *     @SWG\Parameter(name="description", in="formData", required=false, type="string"),
     *     @SWG\Parameter(name="brandId", in="formData", required=true, type="integer"),
     *     @SWG\Parameter(name="files", in="formData", required=false, type="file"),
     *     @SWG\Parameter(name="categories", in="body", required=true,
     *          @SWG\Schema(ref="#/definitions/CategoriesForm")
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
     *
     * @return GenericProduct|ProductCreateForm|string
     * @throws BadRequestHttpException
     * @throws ForbiddenHttpException
     * @throws NotFoundException
     * @throws \yii\base\InvalidArgumentException
     */


    public function actionCreate()
    {
        if (!\Yii::$app->user->can('create')) {
            throw new ForbiddenHttpException('Forbidden');
        }
        $form = new ProductCreateForm();
        $form->load(Yii::$app->request->bodyParams, '');
        if ($form->validate()) {
            try {
                $product = $this->service->create($form);
                Yii::$app->response->setStatusCode(201);
                return $product;
            } catch (\DomainException $e) {
                throw new BadRequestHttpException($e->getMessage());
            }
        }
        return $form;
    }

    /**
     * @SWG\Post(
     *     path="/generic/products/{id}",
     *     tags={"Generic Products"},
     *     description="edit product",
     *     produces={"application/json"},
     *     @SWG\Parameter(name="name", in="formData", required=true, type="string"),
     *     @SWG\Parameter(name="description", in="formData", required=false, type="string"),
     *     @SWG\Parameter(name="brandId", in="formData", required=true, type="integer"),
     *     @SWG\Parameter(name="categories", in="body", required=true,
     *          @SWG\Schema(ref="#/definitions/CategoriesForm")
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
     * @return GenericProduct|ProductCreateForm
     * @throws BadRequestHttpException|NotFoundException
     *
     * @param $id
     * @return GenericProduct
     * @throws BadRequestHttpException
     * @throws NotFoundException
     * @throws \yii\base\InvalidArgumentException
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
     *     path="/generic/products/{id}",
     *     tags={"Generic Products"},
     *     description="Send the product id",
     *     @SWG\Parameter(name="id", in="path", required=true, type="integer"),
     *     @SWG\Response(
     *         response=200,
     *         description="Success response",
     *         @SWG\Schema(ref="#/definitions/ProductData")
     *     ),
     *     security={{"Bearer": {}}}
     * )
     *
     * @param $id
     * @return GenericProduct
     * @throws NotFoundException
     */
    public function actionView($id)
    {
        return $this->repository->get($id);
    }


    /**
     * @SWG\Put(
     *     path="/generic/products/{product_id}/{modification_id}/{photo_id}",
     *     tags={"Generic Products"},
     *     @SWG\Parameter(name="product_id", in="path", required=true, type="integer"),
     *     @SWG\Parameter(name="modification_id", in="path", required=true, type="integer"),
     *     @SWG\Parameter(name="photo_id", in="path", required=true, type="integer"),
     *     @SWG\Response(
     *         response=200,
     *         description="Success response"
     *     ),
     *     security={{"Bearer": {}}}
     * )
     *
     * @param $product_id
     * @param $modification_id
     * @param $photo_id
     * @return bool|string
     * @throws NotFoundException
     * @throws \RuntimeException
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
     * @SWG\Post(
     *     path="/generic/products/{id}/photos",
     *     tags={"Generic Products"},
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
     *
     * @param $id
     * @return GenericProduct
     * @throws NotFoundHttpException
     * @throws \RuntimeException
     * @throws \yii\base\InvalidArgumentException
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
     *     path="/generic/products/{id}/photos/{photo_id}",
     *     tags={"Generic Products"},
     *     description="delete photo",
     *     @SWG\Parameter(name="id", in="path", required=true, type="integer"),
     *     @SWG\Parameter(name="photo_id", in="path", required=true, type="integer"),
     *     @SWG\Response(
     *         response=200,
     *         description="Success response"
     *     ),
     *     security={{"Bearer": {}}}
     * )
     *
     * @param $id
     * @param $photo_id
     * @return bool
     * @throws NotFoundException
     * @throws \DomainException
     * @throws \RuntimeException
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
     *     path="/generic/products/{id}/photos/{photo_id}/up",
     *     tags={"Generic Products"},
     *     description="move up photo",
     *     @SWG\Parameter(name="id", in="path", required=true, type="integer"),
     *     @SWG\Parameter(name="photo_id", in="path", required=true, type="integer"),
     *     @SWG\Response(
     *         response=200,
     *         description="Success response"
     *     ),
     *     security={{"Bearer": {}}}
     * )
     *
     * @param $id
     * @param $photo_id
     * @return bool
     * @throws NotFoundException
     * @throws \DomainException
     * @throws \RuntimeException
     */
    public function actionMovePhotoUp($id, $photo_id)
    {
        $this->service->movePhotoUp($id, $photo_id);
        return true;
    }

    /**
     * @SWG\Patch(
     *     path="/generic/products/{id}/photos/{photo_id}/down",
     *     tags={"Generic Products"},
     *     description="move down photo",
     *     @SWG\Parameter(name="id", in="path", required=true, type="integer"),
     *     @SWG\Parameter(name="photo_id", in="path", required=true, type="integer"),
     *     @SWG\Response(
     *         response=200,
     *         description="Success response"
     *     ),
     *     security={{"Bearer": {}}}
     * )
     *
     * @param $id
     * @param $photo_id
     * @return bool
     * @throws NotFoundException
     * @throws \DomainException
     * @throws \RuntimeException
     */
    public function actionMovePhotoDown($id, $photo_id)
    {
        $this->service->movePhotoDown($id, $photo_id);
        return true;
    }

    /**
     * @SWG\Get(
     *     path="/generic/products/{id}/ratings",
     *     tags={"Generic Products"},
     *     description="Send the product id, return this generic ratings",
     *     @SWG\Parameter(name="id", in="path", required=true, type="integer"),
     *     @SWG\Response(
     *         response=200,
     *         description="Success response",
     *     ),
     *     security={{"Bearer": {}}}
     * )
     *
     * @param $id
     * @return \box\entities\generic\GenericRating[]
     * @throws NotFoundException
     */
    public function actionViewRatings($id)
    {
        $product = $this->readRepository->find($id);
        return $product->ratings;
    }

    /**
     * @SWG\Post(
     *     path="/generic/products/{id}/ratings",
     *     tags={"Generic Products"},
     *     description="added ratings",
     *     @SWG\Parameter(name="id", in="path", required=true, type="integer"),
     *     @SWG\Parameter(name="names", in="formData", required=false, type="string"),
     *     @SWG\Response(
     *         response=200,
     *         description="Success response",
     *         @SWG\Schema(ref="#/definitions/ProductData")
     *     ),
     *     security={{"Bearer": {}}}
     * )
     *
     * @param $id
     * @return GenericProduct|RatingsForm
     * @throws NotFoundHttpException
     * @throws \RuntimeException
     * @throws \yii\base\InvalidArgumentException
     */
    public function actionAddRatings($id)
    {
        $form = new RatingsForm();
        $form->load(Yii::$app->request->bodyParams, '');
        if ($form->validate()) {
            try {
                $product = $this->service->addRatings($id, $form);
                $response = \Yii::$app->getResponse();
                $response->setStatusCode(202);
                return $product;
            } catch (NotFoundHttpException $e) {
                throw new NotFoundHttpException($e->getMessage());
            }
        }
        return $form;
    }

    /**
     * @SWG\Delete(
     *     path="/generic/products/{id}/ratings/{rating_id}",
     *     tags={"Generic Products"},
     *     description="delete generic rating",
     *     @SWG\Parameter(name="id", in="path", required=true, type="integer"),
     *     @SWG\Parameter(name="rating_id", in="path", required=true, type="integer"),
     *     @SWG\Response(
     *         response=200,
     *         description="Success response"
     *     ),
     *     security={{"Bearer": {}}}
     * )
     *
     * @param $id
     * @param $rating_id
     * @return bool
     * @throws NotFoundException
     * @throws \DomainException
     * @throws \RuntimeException
     */
    public function actionDeleteRating($id, $rating_id)
    {
        try {
            $this->service->removeRating($id, $rating_id);
        } catch (\DomainException $e) {
            throw new \DomainException($e->getMessage());
        }
        return true;
    }


}