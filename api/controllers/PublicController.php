<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace api\controllers;

use box\readModels\ProductReadRepository;
use box\readModels\UserReadRepository;
use box\repositories\NotFoundException;
use yii\rest\Controller;

class PublicController extends Controller
{
    public $users;
    public $products;

    public function __construct(string $id, $module, UserReadRepository $users, ProductReadRepository $products, array $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->users = $users;
        $this->products = $products;
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
 *
 * )
 */