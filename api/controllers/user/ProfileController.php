<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace api\controllers\user;

use api\controllers\BearerController;
use box\entities\User;
use box\helpers\UserHelper;
use Yii;

class ProfileController extends BearerController
{
    public function actionIndex()
    {
        return $this->serialize();
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


    protected function serialize()
    {
        $user = $this->findUser();
        return[
            'id' => $user->id,
            'email' => $user->email,
            'phone' => $user->phone,
            'createdAt' => $user->created_at,
            'status' => UserHelper::getStatus($user->status),
            'name' => $user->profile->name,
            'lastName' => $user->profile->last_name,
            'birthDate' => $user->profile->date_of_birth,
            'photo' => $user->profile->getPhoto()
        ];
    }
}