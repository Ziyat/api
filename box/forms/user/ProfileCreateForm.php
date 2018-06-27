<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace box\forms\user;


use box\entities\User;
use yii\base\Model;
use yii\web\UploadedFile;
use Yii;

/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 * Class ProfileCreateForm
 * @package box\forms\user
 * @property $photo
 * @property $name
 * @property $lastName
 * @property $birthDate
 * @property $userId
 */
class ProfileCreateForm extends Model
{
    public $photo;
    public $name;
    public $lastName;
    public $birthDate;
    public $userId;


    public function rules()
    {
        return [
            [['name', 'lastName'], 'string'],
            [['birthDate'], 'date', 'format' => 'php:d-m-Y'],
            [['userId'], 'integer'],
            [['userId'], 'exist',
                'skipOnError' => true,
                'targetClass' => User::class,
                'targetAttribute' => ['userId' => 'id']
            ],
            ['photo', 'image'],
        ];
    }


    public function beforeValidate(): bool
    {
        if (parent::beforeValidate()) {
            $this->photo = UploadedFile::getInstance($this, 'photo');
            if ($this->photo) {
                $this->photo->name = Yii::$app->security->generateRandomString() . '.' . $this->photo->extension;
            }

            return true;
        }
        return false;
    }

}