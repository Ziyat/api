<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace api\controllers\user;


use api\controllers\BearerController;
use box\entities\user\User;
use box\forms\user\AddressForm;
use box\readModels\UserReadRepository;
use box\services\UserService;
use Yii;
use yii\helpers\Url;
use yii\web\BadRequestHttpException;

/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 * Class AddressController
 * @package controllers\user
 * @property  UserService $service
 * @property  UserReadRepository $userReadModel
 */
class AddressController extends BearerController
{
    public $service;
    public $userReadModel;

    public function __construct(
        string $id,
        $module,
        UserService $service,
        UserReadRepository $readRepository,
        array $config = []
    )
    {
        parent::__construct($id, $module, $config);
        $this->service = $service;
        $this->userReadModel = $readRepository;
    }


    /**
     * @SWG\Get(
     *     path="/user/addresses",
     *     tags={"addresses"},
     *     description="Return user addresses",
     *     @SWG\Parameter(name="id", in="path", required=true, type="integer"),
     *     @SWG\Response(
     *         response=200,
     *         description="Success response user addresses",
     *     ),
     *     security={{"Bearer": {}}}
     * ),
     * @return \box\entities\user\Address
     * @throws \box\repositories\NotFoundException
     */
    public function actionIndex()
    {
        return $this->userReadModel->getUserAddresses(Yii::$app->user->id);

    }

    /**
     * @SWG\Post(
     *     path="/user/addresses",
     *     tags={"addresses"},
     *     description="add address",
     *     @SWG\Parameter(name="name", in="path", required=true, type="string"),
     *     @SWG\Parameter(name="phone", in="path", required=false, type="string"),
     *     @SWG\Parameter(name="country_id", in="path", required=true, type="integer"),
     *     @SWG\Parameter(name="address_line_1", in="path", required=true, type="string"),
     *     @SWG\Parameter(name="address_line_2", in="path", required=false, type="string"),
     *     @SWG\Parameter(name="city", in="path", required=true, type="string"),
     *     @SWG\Parameter(name="state", in="path", required=false, type="string"),
     *     @SWG\Parameter(name="index", in="path", required=true, type="string"),
     *     @SWG\Parameter(name="default", in="path", required=false, type="boolean"),
     *     @SWG\Response(
     *         response=200,
     *         description="Success response user Profile",
     *         @SWG\Schema(ref="#/definitions/User")
     *     ),
     *     security={{"Bearer": {}}}
     * )
     * @return AddressForm|User|array
     * @throws \yii\base\InvalidArgumentException|BadRequestHttpException
     */
    public function actionAdd()
    {
        $form = new AddressForm();

        $form->load(Yii::$app->request->bodyParams, '');
        if ($form->validate()) {
            try {
                $user = $this->service->addressAdd(Yii::$app->user->id, $form);
                $response = \Yii::$app->getResponse();
                $response->setStatusCode(201);
                $response->getHeaders()->set('Location', Url::to(['user/products/' . $user->id], true));
                return $user;
            } catch (\DomainException $e) {
                throw new BadRequestHttpException($e->getMessage());
            }
        }
        return $form;
    }

    /**
     * @SWG\Post(
     *     path="/user/addresses/{id}",
     *     tags={"addresses"},
     *     description="Edit user address by id",
     *     @SWG\Parameter(name="name", in="path", required=true, type="string"),
     *     @SWG\Parameter(name="phone", in="path", required=false, type="string"),
     *     @SWG\Parameter(name="country_id", in="path", required=true, type="integer"),
     *     @SWG\Parameter(name="address_line_1", in="path", required=true, type="string"),
     *     @SWG\Parameter(name="address_line_2", in="path", required=false, type="string"),
     *     @SWG\Parameter(name="city", in="path", required=true, type="string"),
     *     @SWG\Parameter(name="state", in="path", required=false, type="string"),
     *     @SWG\Parameter(name="index", in="path", required=true, type="string"),
     *     @SWG\Parameter(name="default", in="path", required=false, type="boolean"),
     *     @SWG\Response(
     *         response=200,
     *         description="Success response user Profile",
     *         @SWG\Schema(ref="#/definitions/User")
     *     ),
     *     security={{"Bearer": {}}}
     * )
     * @throws \Exception
     */
    public function actionEdit($id)
    {
        $address = $this->userReadModel->getUserAddress(Yii::$app->user->id, $id);
        $form = new AddressForm($address);
        $form->load(Yii::$app->request->bodyParams, '');
        if ($form->validate()) {
            try {
                $user = $this->service->addressEdit(Yii::$app->user->id, $form);
                $response = \Yii::$app->getResponse();
                $response->setStatusCode(202);
                return $user;
            } catch (\DomainException $e) {
                throw new BadRequestHttpException($e->getMessage());
            }
        }
        return $form;
    }

    /**
     * @SWG\Delete(
     *     path="/user/addresses/{id}",
     *     tags={"addresses"},
     *     description="Delete user address by id",
     *     @SWG\Parameter(name="id", in="path", required=true, type="integer"),
     *     @SWG\Response(
     *         response=200,
     *         description="Success response bool",
     *     ),
     *     security={{"Bearer": {}}}
     * ),
     * @param $id
     * @return bool
     * @throws BadRequestHttpException
     * @throws \yii\base\InvalidArgumentException
     */
    public function actionRemove($id)
    {
        try {
            $this->service->addressRemove(Yii::$app->user->id, $id);
            $response = \Yii::$app->getResponse();
            $response->setStatusCode(204);
            return true;
        } catch (\DomainException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

    }

    /**
     * @SWG\Patch(
     *     path="/user/addresses/{id}/default",
     *     tags={"addresses"},
     *     description="Delete user address by id",
     *     @SWG\Parameter(name="id", in="path", required=true, type="integer"),
     *     @SWG\Response(
     *         response=200,
     *         description="Success response User object",
     *         @SWG\Schema(ref="#/definitions/User")
     *     ),
     *     security={{"Bearer": {}}}
     * ),
     * @param $id
     * @return User
     * @throws BadRequestHttpException
     * @throws \yii\base\InvalidArgumentException
     */

    public function actionChangeDefault($id)
    {
        try {
            $user = $this->service->addressChangeDefault(Yii::$app->user->id, $id);
            $response = \Yii::$app->getResponse();
            $response->setStatusCode(202);
            return $user;
        } catch (\DomainException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

    }


}