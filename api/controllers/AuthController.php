<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace api\controllers;

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
        if($form->validate()){
            try {
                $form->setParams();
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

    protected function verbs()
    {
        return [
            'login' => ['post'],
            'signup' => ['post'],
        ];
    }
}