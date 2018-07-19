<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace api\controllers;

use box\entities\user\User;
use box\forms\auth\LoginForm;
use box\forms\auth\PasswordResetRequestForm;
use box\forms\auth\SetPasswordForm;
use box\forms\auth\SignupForm;
use box\services\UserService;
use yii\rest\Controller;
use Yii;
use yii\web\BadRequestHttpException;

class AuthController extends Controller
{
    public $service;

    public function __construct(string $id, $module, UserService $service, $config = [])
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
     *         @SWG\Schema(ref="#/definitions/Token")
     *     )
     * )
     */

    public function actionLogin()
    {
        $form = new LoginForm();

        $form->load(Yii::$app->request->bodyParams, '');

        return $form->auth() ?: $form;
    }

    /**
     * @SWG\Post(
     *     path="/signup",
     *     tags={"Sign up"},
     *     @SWG\Parameter(name="login", in="formData", required=true, type="string"),
     *     @SWG\Parameter(name="password", in="formData", required=true, type="string"),
     *     @SWG\Response(
     *         response="200",
     *         description="Success response",
     *         @SWG\Schema(
     *             type="object",
     *             @SWG\Property(property="activate_token", type="string")
     *         ),
     *     )
     * )
     */

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

    /**
     * @SWG\Get(
     *     path="/activate/{activate_token}",
     *     tags={"Activation User account"},
     *     description="Returns Token",
     *     @SWG\Parameter(name="activation_token", in="path", required=true, type="string"),
     *     @SWG\Response(
     *         response="200",
     *         description="Success response",
     *         @SWG\Schema(ref="#/definitions/Token")
     *     )
     * )
     */

    public function actionActivateUser($token)
    {
        $user = User::Activate($token);
        if (!is_array($user)) {
            return LoginForm::login($user);
        }
        return $user;
    }

    /**
     * @SWG\POST(
     *     path="/forgot",
     *     tags={"Password reset"},
     *     description="Returns Email",
     *     @SWG\Parameter(name="email", in="path", required=true, type="string"),
     *     @SWG\Response(
     *         response="200",
     *         description="Success response",
     *         @SWG\Schema(
     *              type="object",
     *              @SWG\Property(property="email", type="string"),
     *          )
     *     )
     * )
     * @return PasswordResetRequestForm
     * @throws BadRequestHttpException
     */
    public function actionPasswordReset()
    {
        $form = new PasswordResetRequestForm();
        $form->load(Yii::$app->request->bodyParams,'');
        if($form->validate()){
            try{
                $this->service->passwordReset($form);
                $response = Yii::$app->getResponse();
                $response->setStatusCode(200);
            }catch (\Exception $e)
            {
                throw new BadRequestHttpException($e->getMessage());
            }
        }
        return $form;
    }

    /**
     * @SWG\POST(
     *     path="/forgot/set-password/{password_reset_token}",
     *     tags={"Password reset"},
     *     description="Returns Token",
     *     @SWG\Parameter(name="token", in="path", required=true, type="string"),
     *     @SWG\Parameter(name="password", in="formData", required=true, type="string"),
     *     @SWG\Response(
     *         response="205",
     *         description="Success response Reset Content",
     *         @SWG\Schema(ref="#/definitions/Token")
     *     )
     * )
     * @param $token
     * @return SetPasswordForm
     * @throws BadRequestHttpException
     */

    public function actionSetPassword($token)
    {
        $form = new SetPasswordForm();
        $form->load(Yii::$app->request->bodyParams,'');
        if($form->validate()){
            try{
                $user = $this->service->setPassword($token,$form);
                $response = Yii::$app->getResponse();
                $response->setStatusCode(205);
                return LoginForm::login($user);
            }catch (\Exception $e)
            {
                throw new BadRequestHttpException($e->getMessage());
            }
        }
        return $form;
    }
}

/**
 * @SWG\Definition(
 *     definition="Token",
 *     type="object",
 *     @SWG\Property(property="token", type="string"),
 *     @SWG\Property(property="expired", type="integer"),
 * )
 **/