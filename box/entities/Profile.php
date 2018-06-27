<?php

namespace box\entities;

use Yii;
use box\entities\User;
use yiidreamteam\upload\ImageUploadBehavior;

/**
 * This is the model class for table "profiles".
 *
 * @property int $id
 * @property int $user_id
 * @property string $name
 * @property string $last_name
 * @property int $date_of_birth
 * @property string $photo
 *
 * @property User $user
 * @mixin ImageUploadBehavior
 */
class Profile extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%profiles}}';
    }

    public function behaviors()
    {
        return [
            'class' => ImageUploadBehavior::class,
            'attribute' => 'photo',
            'thumbs' => [
                'admin' => ['width' => 100, 'height' => 100],
                'thumb' => ['width' => 480, 'height' => 480],
            ],
            'filePath' => '@staticPath/store/user/[[id]]/[[filename]].[[extension]]',
            'fileUrl' => '@staticUrl/app-images/store/user/[[id]]/[[filename]].[[extension]]',
            'thumbPath' => '@staticPath/cache/user/[[id]]/[[profile]]_[[filename]].[[extension]]',
            'thumbUrl' => '@staticUrl/app-images/cache/user/[[id]]/[[profile]]_[[filename]].[[extension]]',
        ];
    }
//    /**
//     * {@inheritdoc}
//     */
//    public function rules()
//    {
//        return [
//            [['user_id'], 'required'],
//            [['user_id', 'date_of_birth'], 'integer'],
//            [['name', 'last_name'], 'string', 'max' => 255],
//            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
//        ];
//    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'name' => 'Name',
            'last_name' => 'Last Name',
            'date_of_birth' => 'Date Of Birth',
            'photo' => 'Photo',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }


}
