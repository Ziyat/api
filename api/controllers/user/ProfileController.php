<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace api\controllers\user;

use api\controllers\BearerController;
use box\entities\User;
use Yii;

class ProfileController extends BearerController
{
    public function actionIndex()
    {
        return $this->findUser();
    }

    protected function verbs()
    {
        return [
            'index' => ['get'],
        ];
    }

    protected function findUser()
    {
        return User::findOne(Yii::$app->user->id);
    }
}