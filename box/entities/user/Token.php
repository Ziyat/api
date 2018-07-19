<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace box\entities\user;

use yii\db\ActiveRecord;
use Yii;

/**
 * Token entity
 *
 * @property integer $id
 * @property string $user_id
 * @property string $token
 * @property string $expired_at
 *
 */
class Token extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%tokens}}';
    }

    public function generateToken($expire)
    {
        $this->expired_at = $expire;

        $this->token = Yii::$app->security->generateRandomString();

    }


    public function fields()
    {
        return [
            'token' => 'token',
            'expired' => 'expired_at',
        ];
    }

}