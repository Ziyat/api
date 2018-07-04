<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace box\forms\user;


use box\entities\user\User;
use yii\base\Model;
use yii\web\UploadedFile;

class UserEditForm extends Model
{
    public $email;
    public $phone;
    public $password;
    public $name;
    public $lastName;
    public $birthDate;
    public $photo;

    public $_user;

    public function __construct(User $user,$config = [])
    {
        parent::__construct($config);
        $this->email = $user->email;
        $this->phone= $user->phone;
        $this->name = $user->profile->name;
        $this->lastName = $user->profile->last_name;
        if($user->profile->date_of_birth){
            $this->birthDate = date('d-m-Y',$user->profile->date_of_birth);
        }
        $this->photo = $user->profile->photo;
        $this->_user = $user;

    }

    public function rules()
    {
        return [

            ['password', 'string', 'min' => 6],
            ['email', 'email'],
            [['name','lastName','lastName'], 'string'],
            [['birthDate'], 'date', 'format' => 'php:d-m-Y'],
            ['photo', 'file', 'extensions' => 'jpeg, gif, png, jpg'],
        ];
    }

    public function beforeValidate(): bool
    {
        if (parent::beforeValidate()) {
            $this->photo = UploadedFile::getInstanceByName('photo');
            $this->setParams('data');
            if ($this->photo) {
                $this->photo->name = \Yii::$app->security->generateRandomString() . '.' . $this->photo->extension;
            }
            return true;
        }
        return false;
    }

    protected function setParams($name)
    {

        if($data = $this->UploadedData($name))
        {
            foreach ($data as $key => $values)
            {
                switch ($key){
                    case 'name':
                        $this->name = $values;
                        break;
                    case 'lastName':
                        $this->lastName = $values;
                        break;
                    case 'birthDate':
                        $this->birthDate = $values;
                        break;
                    case 'email':
                        $this->email = $values;
                        break;
                    case 'password':
                        $this->password = $values;
                        break;
                }
            }
        }

    }



    protected function UploadedData($name)
    {

        if($file = UploadedFile::getInstanceByName($name))
        {
            $data = file_get_contents($file->tempName);
            return json_decode($data);
        }
        return false;
    }
}