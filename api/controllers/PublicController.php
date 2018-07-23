<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace api\controllers;

use box\readModels\ProductReadRepository;
use box\readModels\UserReadRepository;
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
     *         @SWG\Schema(ref="#/definitions/Users")
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
     * )
     */

    public function actionUser($id)
    {
        return $this->users->getUser($id);
    }

    /**
     * @SWG\Get(
     *     path="/public/user/{id}/products",
     *     tags={"Public"},
     *     description="Public user products by id, return user products array data",
     *     @SWG\Parameter(name="id", in="path", required=true, type="integer"),
     *     @SWG\Response(
     *         response=200,
     *         description="Success response",
     *         @SWG\Schema(ref="#/definitions/User")
     *     ),
     * )
     */

    public function actionUserProducts($id)
    {
        return $this->products->getUserProducts($id);
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