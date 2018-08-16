<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace box\entities\user;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use Yii;

/**
 * Token entity
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $token
 * @property integer $expired_at
 * @property User $user
 *
 */
class Token extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%tokens}}';
    }

    /**
     * @param $expire
     * @throws \yii\base\Exception
     */
    public function generateToken($expire)
    {
        $this->expired_at = $expire;
        $this->token = Yii::$app->security->generateRandomString();
    }


    public function fields()
    {
        return [
            'token' => 'token',
            'refresherToken' => function(self $model){
                return $model->user->auth_key;
            },
            'expired' => 'expired_at',
        ];
    }

    public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::class,['id' => 'user_id']);
    }

}