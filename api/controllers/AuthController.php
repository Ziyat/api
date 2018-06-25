<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace api\controllers;

use box\entities\User;
use box\forms\auth\LoginForm;
use box\forms\auth\SignupForm;
use box\services\auth\AuthService;
use yii\rest\Controller;
use Yii;


class AuthController extends Controller
{
    public $service;

    public function __construct(string $id, $module, AuthService $service, $config = [])
    {
        parent::__construct($id, $module, $config);

        $this->service = $service;
    }

    /**
     * @SWG\Post(
     *     path="/login",
     *     tags={"Login"},
     *     @SWG\Parameter(name="login", in="formData", required=true, type="string"),
     *     @SWG\Parameter(name="password", in="formData", required=true, type="string"),
     *     @SWG\Response(
     *         response="200",
     *         description="Success response",
     *         @SWG\Schema(
     *             type="object",
     *             @SWG\Property(property="token", type="string"),
     *             @SWG\Property(property="expired", type="integer")
     *         ),
     *     )
     * )
     */

    public function actionLogin()
    {
        $form = new LoginForm();

        $form->load(Yii::$app->request->bodyParams, '');

        return $form->auth() ?: $form;
    }


    public function actionSignup()
    {

        $form = new SignupForm();
        $form->load(Yii::$app->request->bodyParams, '');
        if ($form->validate()) {
            try {
                $user = $this->service->signup($form);
                return $user;
            } catch (\DomainException $e) {
                return [
                    'field' => 'signup',
                    'message' => $e->getMessage(),
                ];
            }
        }


        return $form;
    }

    public function actionActivateUser($token)
    {
        $user = User::Activate($token);
        if (!is_array($user)) {
            return LoginForm::login($user);
        }
        return $user;
    }


    protected function verbs()
    {
        return [
            'login' => ['post'],
            'signup' => ['post'],
            'activate-user' => ['get'],
        ];
    }
}