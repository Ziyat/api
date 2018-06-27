<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace forms\user;


use box\entities\User;
use yii\web\UploadedFile;

class ProfileCreateForm
{
    public $gender;
    public $photo;
    public $name;
    public $bio;
    public $birthDate;
    public $userId;

    public function rules()
    {
        return [
            [['name', 'bio'], 'string'],
            [['gender'], 'integer'],
            [['birthDate'], 'date','format' => 'php:d-m-Y'],
            [['userId'], 'integer'],
            [['userId'], 'exist',
                'skipOnError' => true,
                'targetClass' => User::class,
                'targetAttribute' => ['userId' => 'id']
            ],
            ['photo', 'file', 'extensions' => 'jpeg, gif, png, jpg'],
        ];
    }


    public function beforeValidate(): bool
    {
        if (parent::beforeValidate()) {
            $this->photo = UploadedFile::getInstance($this, 'photo');
            if($this->photo){
                $this->photo->name = \Yii::$app->security->generateRandomString().'.'. $this->photo->extension;
            }
            return true;
        }
        return false;
    }

}