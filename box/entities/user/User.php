<?php
/**
 * Created by Madetec-Solution.
 * User: Mirkhanov Z.C.
 */

namespace box\entities\user;

use box\entities\shop\product\Product;
use box\entities\user\queries\UserQuery;
use box\forms\auth\SignupForm;
use box\helpers\UserHelper;
use box\repositories\NotFoundException;
use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\web\UnauthorizedHttpException;

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
 * @property integer $private
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 * @property string $role role
 * @property Profile $profile
 *
 * @property Token[] $tokens
 * @property Token $token
 *
 * @property Product[] $products
 *
 * @property User[] $notApproveFollowers
 * @property User[] $notApproveFollowing
 *
 * @property Follower[] $notApproveFollowersAssignments
 * @property Follower[] $notApproveFollowingAssignments
 *
 * @property User[] $approveFollowers
 * @property User[] $approveFollowing
 *
 * @property Follower[] $approveFollowersAssignments
 * @property Follower[] $approveFollowingAssignments
 *
 * @property User[] $followers
 * @property User[] $following
 *
 * @property Follower[] $followersAssignments
 * @property Follower[] $followingAssignments
 *
 * @property Address[] $addresses
 * @property PushToken[] $pushTokens
 */
class User extends ActiveRecord implements IdentityInterface
{
    //use InstantiateTrait;

    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;
    const STATUS_WAIT = 20;

    const ACTIVATE_TOKEN = 'activate';
    const PASSWORD_TOKEN = 'password reset';

    const PRIVATE = 1;
    const NOT_PRIVATE = 0;

    public function init()
    {
        $this->on(self::ACTIVATE_TOKEN, [Yii::$app->emailService, 'sendActivateToken']);
        $this->on(self::PASSWORD_TOKEN, [Yii::$app->emailService, 'sendResetPasswordToken']);
        parent::init();
    }

    public static function signup(SignupForm $form)
    {
        $user = new static();
        $user->email = $form->email ?: null;
        $user->phone = $form->phone ?: null;
        $user->setPassword($form->password);
        $user->created_at = time();
        $user->status = self::STATUS_WAIT;
        $user->generateAuthKey();
        $user->generateActivateToken();
        $user->profile = Profile::create($form->profile);
        return $user;
    }

    /**
     * @param $email
     * @param $phone
     * @param $password
     * @param Profile $profile
     * @throws \yii\base\Exception
     */

    public function edit($email, $phone, $password, Profile $profile)
    {
        $this->email = $email;
        $this->phone = $phone;
        $this->profile = $profile;
        if ($password) {
            $this->setPassword($password);
        }
        $this->updated_at = time();
    }

    public function setPushToken($token, $service)
    {
        $pushTokens = $this->pushTokens;
        foreach ($pushTokens as $pushToken) {
            if ($token == $pushToken->token) {
                return;
            }
        }
        $pushTokens[] = PushToken::create($token, $service);
        $this->pushTokens = $pushTokens;
    }

    public function setAddress(
        $name,
        $phone,
        $country_id,
        $address_line_1,
        $address_line_2,
        $city,
        $state,
        $index,
        $default
    )
    {
        $addresses = $this->addresses;
        $default = count($addresses) ? $default : 1;
        foreach ($addresses as $address) {
            if ($default) {
                $address->nonDefault();
            }
        }
        $addresses[] = Address::create(
            $name,
            $phone,
            $country_id,
            $address_line_1,
            $address_line_2,
            $city,
            $state,
            $index,
            $default
        );

        $this->addresses = $addresses;
    }

    /**
     * @param $id
     * @param $name
     * @param $phone
     * @param $country_id
     * @param $address_line_1
     * @param $address_line_2
     * @param $city
     * @param $state
     * @param $index
     * @param $default
     * @throws NotFoundException
     */
    public function changeAddress(
        $id,
        $name,
        $phone,
        $country_id,
        $address_line_1,
        $address_line_2,
        $city,
        $state,
        $index,
        $default
    )
    {
        $addresses = $this->addresses;
        foreach ($addresses as $k => $address) {
            if ($address->id == $id) {
                $address->edit(
                    $name,
                    $phone,
                    $country_id,
                    $address_line_1,
                    $address_line_2,
                    $city,
                    $state,
                    $index,
                    $default
                );
                $addresses[$k] = $address;
                $this->addresses = $addresses;
                return;
            }
        }
        throw new NotFoundException('address not found.');
    }


    // followers

    public function setFollow($follow_id, $follow_status)
    {
        $following = $this->followingAssignments;
        foreach ($following as $follow) {
            if ($follow->isFollower($follow_id)) {
                return;
            }
        }
        $following[] = Follower::create($follow_id, $follow_status);

        $this->followingAssignments = $following;
    }

    public function unFollow()
    {
        $this->followingAssignments = [];
    }

    // private

    public function setPrivate($private)
    {
        $this->private = $private;
    }

    public function changePrivate()
    {
        $this->private = $this->private ? self::NOT_PRIVATE : self::PRIVATE;
    }


    // Change Status

    public function setActiveStatus()
    {
        $this->status = self::STATUS_ACTIVE;
    }

    public function setWaitStatus()
    {
        $this->status = self::STATUS_WAIT;
    }

    public function setDeleteStatus()
    {
        $this->status = self::STATUS_DELETED;
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
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * @param mixed $token
     * @param null $type
     * @return array|null|ActiveRecord|IdentityInterface
     * @throws UnauthorizedHttpException
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        /**
         * @var self $user
         */
        $user = null;
        if ($user = static::find()
            ->joinWith('tokens t')
            ->andWhere(['t.token' => $token])
            ->one()) {

            foreach ($user->tokens as $tokenDb){
                if ($tokenDb->token == $token && $tokenDb->expired_at < time()) {
                    throw new UnauthorizedHttpException('token expired');
                }
            }
        }
        return $user;
    }

    public static function findByEmail($email)
    {
        return static::find()->where(['email' => $email])->wait()->one();
    }

    public static function findByPhone($phone)
    {
        return static::find()->where(['phone' => $phone])->wait()->one();
    }

    public static function findByEmailActive($email)
    {
        return static::find()->where(['email' => $email])->active()->one();
    }

    public static function findByPhoneActive($phone)
    {
        return static::find()->where(['phone' => $phone])->active()->one();
    }

    /**
     * Finds user by activate token
     *
     * @param string $token activate token
     * @param string $tokenType
     * @return static|null
     */
    public static function findByToken($token, $tokenType)
    {
        if (!static::isTokenValid($token) && $tokenType == static::PASSWORD_TOKEN) {
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
     * @param $password
     * @return bool
     * @throws \yii\base\InvalidArgumentException
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * @param $password
     * @throws \yii\base\Exception
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     * @throws \yii\base\Exception
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
        $this->password_reset_token = rand(100000, 900000);
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
        $this->activate_token = rand(100000, 900000);
    }

    /**
     * Removes activate token
     */

    public function removeActivateToken()
    {
        $this->activate_token = null;
    }

    //addresses

    public function getAddresses()
    {
        return $this->hasMany(Address::class, ['user_id' => 'id'])->orderBy(['default' => SORT_DESC]);
    }


    // tokens

    public function getTokens()
    {
        return $this->hasMany(Token::class, ['user_id' => 'id']);
    }

    public function getPushTokens()
    {
        return $this->hasMany(PushToken::class, ['user_id' => 'id']);
    }

    public function getToken()
    {
        return $this->hasOne(Token::class, ['user_id' => 'id'])
            ->andWhere(['>','expired_at', time()])
            ->orderBy(['id' => SORT_DESC]);
    }

    // profiles

    public function getProfile()
    {
        return $this->hasOne(Profile::class, ['user_id' => 'id']);
    }

    // products

    public function getProducts(): ActiveQuery
    {
        return $this->hasMany(Product::class, ['created_by' => 'id']);
    }

    // followers

    public function getFollowersAssignments(): ActiveQuery
    {
        return $this->hasMany(Follower::class, ['user_id' => 'id']);
    }

    public function getFollowingAssignments(): ActiveQuery
    {
        return $this->hasMany(Follower::class, ['follower_id' => 'id']);
    }

    public function getFollowers(): ActiveQuery
    {
        return $this->hasMany(User::class, ['id' => 'follower_id'])
            ->via('followersAssignments');
    }

    public function getFollowing(): ActiveQuery
    {
        return $this->hasMany(User::class, ['id' => 'user_id'])
            ->via('followingAssignments');
    }


    public function getNotApproveFollowersAssignments(): ActiveQuery
    {
        return $this->hasMany(Follower::class, ['user_id' => 'id'])
            ->andWhere(['status' => Follower::NOT_APPROVE]);
    }

    public function getNotApproveFollowingAssignments(): ActiveQuery
    {
        return $this->hasMany(Follower::class, ['follower_id' => 'id'])
            ->andWhere(['status' => Follower::NOT_APPROVE]);
    }

    public function getNotApproveFollowers(): ActiveQuery
    {
        return $this->hasMany(User::class, ['id' => 'follower_id'])
            ->via('notApproveFollowersAssignments');
    }

    public function getNotApproveFollowing(): ActiveQuery
    {
        return $this->hasMany(User::class, ['id' => 'user_id'])
            ->via('notApproveFollowingAssignments');
    }

    public function getApproveFollowersAssignments(): ActiveQuery
    {
        return $this->hasMany(Follower::class, ['user_id' => 'id'])
            ->andWhere(['status' => Follower::APPROVE]);
    }

    public function getApproveFollowingAssignments(): ActiveQuery
    {
        return $this->hasMany(Follower::class, ['follower_id' => 'id'])
            ->andWhere(['status' => Follower::APPROVE]);
    }

    public function getApproveFollowers(): ActiveQuery
    {
        return $this->hasMany(User::class, ['id' => 'follower_id'])
            ->via('approveFollowersAssignments');
    }

    public function getApproveFollowing(): ActiveQuery
    {
        return $this->hasMany(User::class, ['id' => 'user_id'])
            ->via('approveFollowingAssignments');
    }


    public function fields()
    {
        return [
            'id' => 'id',
            'name' => function (self $model) {
                return $model->profile->name;
            },
            'lastName' => function (self $model) {
                return $model->profile->last_name;
            },
            'photo' => function (self $model) {
                return $model->profile->getPhoto();
            },
            'status' => function (self $model) {
                return UserHelper::getStatus($model->status);

            },
            'private' => function (self $model) {
                return UserHelper::getPrivate($model->private);

            },
            'birthDate' => function (self $model) {
                return $model->profile->date_of_birth;

            },
            'createdAt' => 'created_at',

            'approveFollowers' => function(self $model){
                return count($model->approveFollowers);
            },
            'notApproveFollowers' => function(self $model){
                return count($model->notApproveFollowers);
            },
            'approveFollowing' => function(self $model){
                return count($model->approveFollowing);
            },
            'notApproveFollowing' => function(self $model){
                return count($model->notApproveFollowing);
            },
            "productsActive" => function (self $model) {
                return $model->getProducts()->andWhere(['status' => Product::STATUS_ACTIVE])->count();
            },
            "productsMarket" => function (self $model) {
                return $model->getProducts()->andWhere(['status' => Product::STATUS_MARKET])->count();
            },
            "addresses" => function(self $model){
                $address = $model->getAddresses()->andWhere(['=', 'default', 1])->one();
                return $address ? [
                    'state' => $address->state,
                    'default' => $address->default,
                    'city' => $address->city,
                    'country' => $address->country->name,
                    'code' => $address->country->code,
                ] : null;
            },
            "push-tokens" => function () {
                return $this->pushTokens;
            }
        ];
    }

    public static function find(): UserQuery
    {
        return new UserQuery(static::class);
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
                'relations' => ['profile', 'followingAssignments', 'followersAssignments', 'addresses', 'pushTokens']
            ],

        ];
    }

}