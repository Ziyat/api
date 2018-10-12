<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace box\forms\user;


use yii\base\Model;

class PushTokenForm extends Model
{
    public $token;
    public $service;

    public function rules(): array
    {
        return [
            [['token','service'],'string']
        ];
    }
}