<?php
/**
 * Created by Madetec-Solution.
 * User: Mirkhanov Z.C.
 */

namespace box\entities;

use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\web\NotFoundHttpException;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $activate_token
 * @property string $email
 * @property string $phone
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 * @property Profile $profile
 */
class User extends ActiveRecord implements IdentityInterface
{
    //use InstantiateTrait;

    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;
    const STATUS_WAIT = 20;
    const ACTIVATE_TOKEN = 'activate';
    const PASSWORD_TOKEN = 'password reset';

    public static function signup($email, $phone, $password): self
    {
        $user = new static();
        $user->email = $email ?: null;
        $user->phone = $phone ?: null;

        $user->setPassword($password);
        $user->created_at = time();
        $user->status = self::STATUS_WAIT;
        $user->generateAuthKey();
        $user->generateActivateToken();
//        $user->profile = new Profile();
        return $user;
    }


    public static function Activate($token)
    {

        if ($user = static::findByToken($token, static::ACTIVATE_TOKEN)) {
            $user->status = static::STATUS_ACTIVE;
            $user->removeActivateToken();
            $user->save();
            return $user;
        }
        Yii::$app->response->statusCode = 404;
        return [
            'field' => 'User',
            'message' => 'User not found.'
        ];

    }

    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function isWait(): bool
    {
        return $this->status === self::STATUS_WAIT;
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%users}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::class,
            [
                'class' => SaveRelationsBehavior::class,
                'relations' => ['profile']
            ],

        ];
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::find()
            ->joinWith('tokens t')
            ->andWhere(['t.token' => $token])
            ->andWhere(['>', 't.expired_at' , time()])
            ->one();
    }

    public static function findByEmail($email)
    {
        return static::findOne(['email' => $email, 'status' => User::STATUS_WAIT]);
    }


    public static function findByPhone($phone)
    {
        return static::findOne(['phone' => $phone, 'status' => User::STATUS_WAIT]);
    }

    public static function findByEmailActive($email)
    {
        return static::findOne(['email' => $email, 'status' => User::STATUS_ACTIVE]);
    }


    public static function findByPhoneActive($phone)
    {
        return static::findOne(['phone' => $phone, 'status' => User::STATUS_ACTIVE]);
    }

    /**
     * Finds user by activate token
     *
     * @param string $token activate token
     * @return static|null
     */
    public static function findByToken($token, $tokenType)
    {
        if (!static::isTokenValid($token)) {
            return null;
        }
        $condition = null;
        if ($tokenType == static::ACTIVATE_TOKEN) {
            $condition = [
                'activate_token' => $token,
                'status' => self::STATUS_WAIT,
            ];
        }

        if ($tokenType == static::PASSWORD_TOKEN) {
            $condition = [
                'password_reset_token' => $token,
                'status' => self::STATUS_ACTIVE,
            ];
        }


        return static::findOne($condition);
    }


    /**
     * Finds out if activate token is valid
     *
     * @param string $token activate token
     * @return bool
     */
    public static function isTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int)substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }


    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    /**
     * Generates new activate token
     */
    public function generateActivateToken()
    {
        $this->activate_token = rand(1, 10000000) . '_' . time();
    }

    /**
     * Removes activate token
     */
    public function removeActivateToken()
    {
        $this->activate_token = null;
    }


    public function getTokens()
    {
        return $this->hasOne(Token::class, ['user_id' => 'id']);
    }

    public function getProfile()
    {
        return $this->hasOne(Profile::class, ['user_id' => 'id']);
    }
}