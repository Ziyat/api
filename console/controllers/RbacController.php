<?php

namespace console\controllers;

use box\entities\user\User;
use yii\console\Controller;
use Yii;
use const PHP_EOL;

class RbacController extends Controller
{
    public function actionInit()
    {
        $auth = \Yii::$app->authManager;
        $auth->removeAll();

        $create = $auth->createPermission('create');
        $auth->add($create);

        $user = $auth->createRole('user');
        $user->description = 'User';
        $auth->add($user);

        $moderator = $auth->createRole('moderator');
        $moderator->description = 'moderator';
        $auth->add($moderator);

        $administrator = $auth->createRole('administrator');
        $administrator->description = 'administrator';
        $auth->add($administrator);

        // add children role

        $auth->addChild($administrator, $user);
        $auth->addChild($administrator, $moderator);

        $auth->addChild($moderator,$create);
        $auth->addChild($moderator,$user);



        $this->stdout('Done!' . PHP_EOL);
    }

    public function actionTest()
    {
        $request = new \yii\web\Request();

        $request->enableCookieValidation = false;
        $request->enableCsrfValidation = false;
        $request->enableCsrfCookie = false;
        try{
            Yii::$app->set('request', $request);
        }catch (\Exception $e){

        }

        $auth = Yii::$app->authManager;

        $role = $auth->getRole('administrator');

        $user = User::findOne(1);

        $auth->assign($role,$user->id);

        echo PHP_EOL;

    }
}