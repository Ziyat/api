<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace api\controllers\user;


use api\controllers\BearerController;
use box\entities\user\User;
use box\forms\user\AddressForm;
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
 */
class AddressController extends BearerController
{
    public $service;

    public function __construct(
        string $id,
        $module,
        UserService $service,
        array $config = []
    )
    {
        parent::__construct($id, $module, $config);
        $this->service = $service;
    }


    /**
     *  @SWG\Post(
     *     path="/user/addresses",
     *     tags={"addresses"},
     *     description="Returns user",
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
     *  @SWG\GET(
     *     path="/user/addresses/countries",
     *     tags={"addresses"},
     *     description="Returns user",
     *     @SWG\Response(
     *         response=200,
     *         description="Success response user Profile",
     *         @SWG\Schema(ref="#/definitions/User")
     *     ),
     *     security={{"Bearer": {}}}
     * )
     */

    public function actionCountry()
    {
        return $this->service->getCountries();
    }
}