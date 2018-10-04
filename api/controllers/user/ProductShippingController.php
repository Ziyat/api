<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace api\controllers\user;


use api\controllers\BearerController;
use box\forms\shop\product\ProductShippingForm;
use box\forms\shop\shipping\SearchRatesForm;
use box\readModels\ProductShippingReadModel;
use box\services\ProductService;
use Yii;
use yii\web\BadRequestHttpException;

/**
 * Class ProductShippingController
 * @package api\controllers\user
 * @property ProductService $productService
 * @property ProductShippingReadModel $readModel
 */
class ProductShippingController extends BearerController
{
    public $productService;
    public $readModel;

    public function __construct(
        string $id,
        $module,
        ProductService $productService,
        ProductShippingReadModel $readModel,
        array $config = []
    )
    {
        parent::__construct($id, $module, $config);

        $this->productService = $productService;
        $this->readModel = $readModel;
    }


    /**
     * @SWG\Post(
     *     path="user/products/shipping/search",
     *     tags={"User Products"},
     *     description="return 'rates by params' default by default address country_id",
     *     @SWG\Parameter(name="weight", in="formData", required=false, type="number"),
     *     @SWG\Parameter(name="destinations", in="body", required=false,
     *          @SWG\Schema(@SWG\Items())
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Success response",
     *     ),
     *     security={{"Bearer": {}}}
     * )
     * @return array|\yii\db\ActiveRecord[]
     * @throws \box\repositories\NotFoundException
     */
    public function actionSearchRates()
    {
        $form = new SearchRatesForm();
        $form->load(Yii::$app->request->bodyParams, '');

        return $this->readModel->getRates(Yii::$app->user->identity, $form);
    }


    /**
     * @SWG\Get(
     *     path="user/products/shipping/{product_id}",
     *     tags={"User Products"},
     *     description="get user/product shipping",
     *     @SWG\Parameter(name="product_id", in="path", required=true, type="integer"),
     *     @SWG\Response(
     *         response=200,
     *         description="Success response",
     *         @SWG\Schema(ref="#/definitions/UserProductShippingResponse")
     *     ),
     *     security={{"Bearer": {}}}
     * )
     * @param $product_id
     * @return \yii\data\ActiveDataProvider
     * @throws BadRequestHttpException
     */
    public function actionIndex($product_id)
    {
        try {
            return $this->readModel->getAll($product_id);
        } catch (\Exception $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
    }

    /**
     * @SWG\Get(
     *     path="user/products/shipping/{product_id}/{shipping_id}",
     *     tags={"User Products"},
     *     description="get user/product shipping",
     *     @SWG\Parameter(name="product_id", in="path", required=true, type="integer"),
     *     @SWG\Parameter(name="shipping_id", in="path", required=true, type="integer"),
     *     @SWG\Response(
     *         response=200,
     *         description="Success response",
     *         @SWG\Schema(ref="#/definitions/UserProductShippingResponse")
     *     ),
     *     security={{"Bearer": {}}}
     * )
     * @param $product_id
     * @param $id
     * @return \box\entities\shop\product\Shipping
     * @throws BadRequestHttpException
     */
    public function actionView($product_id, $id)
    {
        try {
            return $this->readModel->get($product_id, $id);
        } catch (\Exception $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
    }/**
     * @SWG\Delete(
     *     path="user/products/shipping/{product_id}/{shipping_id}",
     *     tags={"User Products"},
     *     description="get user/product shipping",
     *     @SWG\Parameter(name="product_id", in="path", required=true, type="integer"),
     *     @SWG\Parameter(name="shipping_id", in="path", required=true, type="integer"),
     *     @SWG\Response(
     *         response=200,
     *         description="Success response",
     *         @SWG\Schema(ref="#/definitions/UserProductShippingResponse")
     *     ),
     *     security={{"Bearer": {}}}
     * )
     * @param $product_id
     * @param $shipping_id
     * @throws BadRequestHttpException
     */
    public function actionDelete($product_id, $shipping_id)
    {
        try {
            $this->productService->removeShipping($product_id, $shipping_id);
        } catch (\Exception $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
    }


    /**
     * @SWG\Post(
     *     path="user/products/shipping/{product_id}",
     *     tags={"User Products"},
     *     description="get user/product shipping",
     *     @SWG\Parameter(name="product_id", in="path", required=true, type="integer"),
     *     @SWG\Parameter(name="free_shipping_type", in="formData", required=true, type="integer"),
     *     @SWG\Parameter(name="rate_id", in="formData", required=false, type="integer"),
     *     @SWG\Parameter(name="price", in="formData", required=false, type="number"),
     *     @SWG\Parameter(name="countryIds", in="body", required=false,
     *          @SWG\Schema(@SWG\Items())
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Success response",
     *         @SWG\Schema(ref="#/definitions/UserProductShippingResponse")
     *     ),
     *     security={{"Bearer": {}}}
     * )
     * @param $product_id
     * @return \box\entities\shop\product\Shipping|ProductShippingForm
     * @throws BadRequestHttpException
     * @throws \yii\base\InvalidArgumentException
     */

    public function actionAdd($product_id)
    {
        $form = new ProductShippingForm();
        $form->load(Yii::$app->request->bodyParams, '');
        if ($form->validate()) {
            try {
                $shipping = $this->productService->setShipping($product_id, $form);
                return $shipping;
            } catch (\Exception $e) {
                throw new BadRequestHttpException($e->getMessage());
            }
        }

        return $form;
    }



    /**
     * @SWG\Patch(
     *     path="user/products/shipping/{product_id}/{shipping_id}/free",
     *     tags={"User Products"},
     *     description="get user/product shipping",
     *     @SWG\Parameter(name="product_id", in="path", required=true, type="integer"),
     *     @SWG\Response(
     *         response=200,
     *         description="Success response",
     *         @SWG\Schema(ref="#/definitions/UserProductShippingResponse")
     *     ),
     *     security={{"Bearer": {}}}
     * )
     *
     * @param $product_id
     * @param $shipping_id
     * @return \box\entities\shop\product\Shipping
     * @throws BadRequestHttpException
     */
    public function actionFree($product_id, $shipping_id)
    {
        try {
            $shipping = $this->productService->freeShipping($product_id,$shipping_id);
            return $shipping;
        } catch (\Exception $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
    }

    /**
     * @SWG\Patch(
     *     path="user/products/shipping/{product_id}/{shipping_id}/no-free",
     *     tags={"User Products"},
     *     description="get user/product shipping",
     *     @SWG\Parameter(name="product_id", in="path", required=true, type="integer"),
     *     @SWG\Response(
     *         response=200,
     *         description="Success response",
     *         @SWG\Schema(ref="#/definitions/UserProductShippingResponse")
     *     ),
     *     security={{"Bearer": {}}}
     * )
     *
     * @param $product_id
     * @param $shipping_id
     * @return \box\entities\shop\product\Shipping
     * @throws BadRequestHttpException
     */
    public function actionNoFree($product_id, $shipping_id)
    {
        try {
            $shipping = $this->productService->noFreeShipping($product_id,$shipping_id);
            return $shipping;
        } catch (\Exception $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
    }

    /**
     * @SWG\Patch(
     *     path="user/products/shipping/{product_id}/{shipping_id}/pickup",
     *     tags={"User Products"},
     *     description="get user/product shipping",
     *     @SWG\Parameter(name="product_id", in="path", required=true, type="integer"),
     *     @SWG\Response(
     *         response=200,
     *         description="Success response",
     *         @SWG\Schema(ref="#/definitions/UserProductShippingResponse")
     *     ),
     *     security={{"Bearer": {}}}
     * )
     *
     * @param $product_id
     * @param $shipping_id
     * @return \box\entities\shop\product\Shipping
     * @throws BadRequestHttpException
     */
    public function actionPickup($product_id, $shipping_id)
    {
        try {
            $shipping = $this->productService->pickupShipping($product_id,$shipping_id);
            return $shipping;
        } catch (\Exception $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
    }
}


/**
 * @SWG\Definition(
 *     definition="UserProductShippingResponse",
 *     type="object",
 *     @SWG\Property(property="id", type="integer"),
 *     @SWG\Property(property="product_id", type="integer"),
 *     @SWG\Property(property="rate", type="object", ref="#/definitions/Rate"),
 *     @SWG\Property(property="destinations", type="array",
 *          @SWG\Items(
 *              @SWG\Property(property="id", type="integer"),
 *              @SWG\Property(property="name", type="string"),
 *              @SWG\Property(property="code", type="string"),
 *     )),
 *     @SWG\Property(property="price", type="number"),
 *     @SWG\Property(property="free_shipping_type", type="integer"),
 * )
 */