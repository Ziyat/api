<?php

namespace box\entities\user;

use box\forms\user\ProfileCreateForm;
use Yii;
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

    public static function create(ProfileCreateForm $form)
    {
        $profile = new static();
        $profile->photo = $form->photo;
        $profile->last_name = $form->lastName;
        $profile->name = $form->name;
        $profile->date_of_birth = $form->birthDate;

        return $profile;
    }

    public function edit($name,$lastName,$birthDate,$photo)
    {
        $this->name = $name;
        $this->photo = $photo;
        $this->last_name = $lastName;
        $this->date_of_birth = strtotime($birthDate);
    }


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
            [
                'class' => ImageUploadBehavior::class,
                'attribute' => 'photo',
                'thumbs' => [
                    'admin' => ['width' => 100, 'height' => 100],
                    'thumb' => ['width' => 480, 'height' => 480],
                ],
                'filePath' => '@staticPath/store/profile/[[id]]/[[filename]].[[extension]]',
                'fileUrl' => '@staticUrl/store/profile/[[id]]/[[filename]].[[extension]]',
                'thumbPath' => '@staticPath/cache/profile/[[id]]/[[profile]]_[[filename]].[[extension]]',
                'thumbUrl' => '@staticUrl/cache/profile/[[id]]/[[profile]]_[[filename]].[[extension]]',
            ]
        ];
    }

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
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public function getPhoto($profile = 'thumb')
    {
        return $this->getThumbFileUrl('photo', $profile,Yii::getAlias('@staticUrl').'/empty/no-photo.jpg');
    }


}
