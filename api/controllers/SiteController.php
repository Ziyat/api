<?php

namespace api\controllers;

use box\entities\User;
use box\forms\LoginForm;
use yii\rest\Controller;
use Yii;

class SiteController extends Controller
{

    public function actionIndex()
    {
        return [
            'version' => '1.0.0',
        ];
    }

    public function actionLogin()
    {
        $form = new LoginForm();
        $form->load(Yii::$app->request->bodyParams, '');
        if ($token = $form->auth()) {
            return [
                'token' => $token->token,
                'expired' => $token->expired_at,
            ];
        }else{
            return $form;
        }

    }

    protected function verbs()
    {
        return [
            'login' => ['post'],
        ];
    }
}
