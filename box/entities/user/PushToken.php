<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace box\entities\user;


use yii\db\ActiveRecord;

/**
 * @property integer $id
 * @property integer $user_id
 * @property string $token
 * @property string $service
 */
class PushToken extends ActiveRecord
{
    public static function create($token, $service)
    {
        $pushToken = new static();
        $pushToken->token = $token;
        $pushToken->service = $service ?: 'Firebase';
        return $pushToken;
    }

    public static function tableName(): string
    {
        return '{{%push_tokens}}';
    }
}