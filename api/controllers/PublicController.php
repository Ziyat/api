<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace api\controllers;

use box\forms\publicForms\UserProductShippingForm;
use box\readModels\CountryReadModel;
use box\readModels\ProductReadRepository;
use box\readModels\UserReadRepository;
use box\repositories\NotFoundException;
use yii\rest\Controller;

class PublicController extends Controller
{
    public $users;
    public $products;
    public $countries;

    public function __construct(
        string $id,
        $module,
        UserReadRepository $users,
        ProductReadRepository $products,
        CountryReadModel $countryReadModel,
        array $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->users = $users;
        $this->products = $products;
        $this->countries = $countryReadModel;
    }


    /**
     * @SWG\Post(
     *     path="/public/user/products/{product_id}/shipping",
     *     tags={"Public"},
     *     description="return product shipping",
     *     @SWG\Parameter(name="product_id", in="path", required=true, type="integer"),
     *     @SWG\Parameter(name="user_id", in="formData", required=false, type="boolean"),
     *     @SWG\Parameter(name="free", in="formData", required=false, type="boolean"),
     *     @SWG\Parameter(name="pickup", in="formData", required=false, type="boolean"),
     *     @SWG\Response(
     *         response=200,
     *         description="Success response",
     *         @SWG\Schema(
     *              type="object",
     *              @SWG\Property(property="destinations", type="object",
     *                  @SWG\Property(property="free", type="array", @SWG\Items(ref="#/definitions/UserProductShippingResponse")),
     *                  @SWG\Property(property="pickup", type="array", @SWG\Items(ref="#/definitions/UserProductShippingResponse")),
     *                  @SWG\Property(property="other", type="array", @SWG\Items(ref="#/definitions/UserProductShippingResponse")),
     *              ),
     *              @SWG\Property(property="free", type="array",@SWG\Items(ref="#/definitions/UserProductShippingResponse")),
     *              @SWG\Property(property="pickup", type="array",@SWG\Items(ref="#/definitions/UserProductShippingResponse")),
     *              @SWG\Property(property="other", type="array",@SWG\Items(ref="#/definitions/UserProductShippingResponse")),
     *          )
     *     ),
     * )
     *
     * @param $product_id
     * @return array
     * @throws NotFoundException
     * @throws \LogicException
     * @throws \yii\base\InvalidArgumentException
     */
    public function actionUserProductsShipping($product_id)
    {
        $form = new UserProductShippingForm();
        $form->load(\Yii::$app->request->bodyParams,'');
        $shipping = $this->products->getShipping($product_id, $form);
        return $shipping;
    }


    /**
     * @SWG\Get(
     *     path="/public/users",
     *     tags={"Public"},
     *     description="Public users, Return users array",
     *     @SWG\Response(
     *         response=200,
     *         description="Success response",
     *         @SWG\Schema(
     *              type="array",
     *              @SWG\Items()
     *          )
     *     ),
     * )
     */

    public function actionUsers()
    {
        return $this->users->getUsers();
    }

    /**
     * @SWG\Get(
     *     path="/public/user/{id}",
     *     tags={"Public"},
     *     description="Public user by id, return user data",
     *     @SWG\Parameter(name="id", in="path", required=true, type="integer"),
     *     @SWG\Response(
     *         response=200,
     *         description="Success response",
     *         @SWG\Schema(ref="#/definitions/User")
     *     ),
     *     @SWG\Response(
     *         response=404,
     *         description="User not found.",
     *     ),
     * )
     * @throws NotFoundException
     */

    public function actionUser($id)
    {
        return $this->users->getUser($id);
    }

    /**
     * @SWG\Get(
     *     path="/public/user/{user_id}/products",
     *     tags={"Public"},
     *     @SWG\Parameter(name="user_id", in="path", required=true, type="integer"),
     *     description="Public user products by user_id, return user products array data",
     *     @SWG\Response(
     *         response=200,
     *         description="Success response",
     *         @SWG\Property(property="products", type="array",
     *          @SWG\Items(ref="#/definitions/ProductData"))
     *     ),
     * )
     * @params $user_id
     */

    public function actionUserProducts($user_id)
    {
        return $this->products->getUserProducts($user_id);
    }

    /**
     * @SWG\Get(
     *     path="/public/user/products/{product_id}",
     *     tags={"Public"},
     *     @SWG\Parameter(name="product_id", in="path", required=true, type="integer"),
     *     description="Public user products by product_id, return user product data",
     *     @SWG\Response(
     *         response=200,
     *         description="Success response",
     *         @SWG\Schema(ref="#/definitions/ProductData")
     *     ),
     * )
     * @params $product_id
     * @throws NotFoundException
     */

    public function actionProductsById($product_id)
    {

        return $this->products->getProductsById($product_id);
    }

    /**
     * @SWG\Get(
     *     path="/public/user/{id}/followers",
     *     tags={"Public"},
     *     @SWG\Parameter(name="id", in="path", required=true, type="integer"),
     *     description="Return user followers array",
     *     @SWG\Response(
     *         response=200,
     *         description="Success response",
     *         @SWG\Schema(
     *              type="array",
     *          @SWG\Items(
     *              @SWG\Property(property="KEY [0] -> {approve}", type="array", @SWG\Items(ref="#/definitions/Profile")),
     *              @SWG\Property(property="KEY [1] -> {not approve}", type="array", @SWG\Items(ref="#/definitions/Profile")),
     *          ),
     *          )
     *     ),
     * )
     */

    public function actionFollowers($user_id)
    {
        return $this->users->getFollowers($user_id);
    }

    /**
     * @SWG\Get(
     *     path="/public/user/{id}/following",
     *     tags={"Public"},
     *     @SWG\Parameter(name="id", in="path", required=true, type="integer"),
     *     description="Return user following array",
     *     @SWG\Response(
     *         response=200,
     *         description="Success response",
     *         @SWG\Schema(
     *              type="array",
     *          @SWG\Items(
     *              @SWG\Property(property="KEY [0] -> {approve}", type="array", @SWG\Items(ref="#/definitions/Profile")),
     *              @SWG\Property(property="KEY [1] -> {not approve}", type="array", @SWG\Items(ref="#/definitions/Profile")),
     *          ),
     *          )
     *     ),
     * )
     */

    public function actionFollowing($user_id)
    {
        return $this->users->getFollowing($user_id);
    }


    /**
     * @SWG\GET(
     *     path="/public/countries",
     *     tags={"Public"},
     *     description="Returns countries",
     *     @SWG\Response(
     *         response=200,
     *         description="Success response",
     *         @SWG\Property(property="countries", type="array",@SWG\Items(ref="#/definitions/Country"))
     *
     *
     *     ),
     * )
     */

    public function actionCountries()
    {
        return $this->countries->getCountries();
    }

    /**
     * @SWG\GET(
     *     path="/public/countries/{id}",
     *     tags={"Public"},
     *     description="Returns country by id",
     *     @SWG\Response(
     *         response=200,
     *         description="Success response",
     *         @SWG\Schema(ref="#/definitions/Country")
     *     ),
     * )
     * @params $id
     */

    public function actionCountry($id)
    {
        return $this->countries->getCountry($id);
    }

    /**
     * @SWG\GET(
     *     path="/public/countries/{code}",
     *     tags={"Public"},
     *     description="Returns country by code ISO-2",
     *     @SWG\Response(
     *         response=200,
     *         description="Success response",
     *         @SWG\Schema(ref="#/definitions/Country")
     *     ),
     * )
     * @params $code
     */

    public function actionCountryByCode($code)
    {
        return $this->countries->getCountryByCode($code);
    }

}

/**
 * @SWG\Definition(
 *     definition="Users",
 *     type="array",
 *     @SWG\Items(ref="#/definitions/User")
 * )
 */

/**
 * @SWG\Definition(
 *     definition="User",
 *     type="object",
 *     @SWG\Property(property="id", type="integer"),
 *     @SWG\Property(property="name", type="string"),
 *     @SWG\Property(property="lastName", type="string"),
 *     @SWG\Property(property="photo", type="string"),
 *     @SWG\Property(property="status", type="string"),
 *     @SWG\Property(property="birthDate", type="string"),
 *     @SWG\Property(property="createdAt", type="integer"),
 *     @SWG\Property(property="approveFollowers", type="integer"),
 *     @SWG\Property(property="notApproveFollowers", type="integer"),
 *     @SWG\Property(property="approveFollowing", type="integer"),
 *     @SWG\Property(property="notApproveFollowing", type="integer"),
 *     @SWG\Property(property="productsActive", type="integer"),
 *
 * )
 */

/**
 * @SWG\Definition(
 *     definition="Country",
 *     type="object",
 *     @SWG\Property(property="id", type="integer"),
 *     @SWG\Property(property="name", type="string"),
 *     @SWG\Property(property="code", type="string"),
 *
 * )
 */

/**
 * @SWG\Definition(
 *     definition="Rate",
 *     type="object",
 *     @SWG\Property(property="id", type="integer"),
 *     @SWG\Property(property="name", type="string"),
 *     @SWG\Property(property="price_type", type="object",
 *          @SWG\Property(property="name", type="string"),
 *          @SWG\Property(property="code", type="integer"),
 *     ),
 *     @SWG\Property(property="price_min", type="number"),
 *     @SWG\Property(property="price_max", type="number"),
 *     @SWG\Property(property="price_fix", type="number"),
 *     @SWG\Property(property="day_min", type="integer"),
 *     @SWG\Property(property="day_max", type="integer"),
 *     @SWG\Property(property="country", type="object",ref="#/definitions/Country"),
 *     @SWG\Property(property="destinations", type="array", @SWG\Items(ref="#/definitions/Country")),
 *     @SWG\Property(property="type", type="object",
 *          @SWG\Property(property="name", type="string"),
 *          @SWG\Property(property="code", type="integer"),
 *     ),
 *     @SWG\Property(property="weight", type="number"),
 *     @SWG\Property(property="width", type="number"),
 *     @SWG\Property(property="height", type="number"),
 *     @SWG\Property(property="length", type="number"),
 * )
 */