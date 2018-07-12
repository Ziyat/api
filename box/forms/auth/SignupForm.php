<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace box\forms\auth;

use box\entities\user\User;
use box\forms\CompositeForm;
use box\forms\user\ProfileCreateForm;
use box\validators\UniqueLoginValidator;

/**
 * Signup form
 * @property $login
 * @property $phone
 * @property $email
 * @property $password
 * @property ProfileCreateForm $profile
 */

class SignupForm extends CompositeForm
{
    public $email;
    public $phone;
    public $login;
    public $password;
    public $profile;

    public function __construct($config = [])
    {
        $this->profile = new ProfileCreateForm();
        parent::__construct($config);
    }


    public function rules()
    {
        return [
            [['password','login'], 'required'],

            ['login', 'trim'],

            // login is validated by validateLogin()
            ['login', 'validateLogin'],

            ['password', 'string', 'min' => 6],
        ];
    }


    public function validateLogin($attribute,$params)
    {


        if (!$this->hasErrors()) {

            $attributeStatus = $this->generateAttributeLabel('status');

            if(!$this->isPhone() && !$this->isEmail()){
                $this->addError($attribute, 'Please enter a valid mobile number or email address.');
            }

            if($this->isEmail()){

                if(User::findByEmail($this->login)){
                    $this->addError($attribute, 'This email address already exists, but not activated');
                    $this->addError($attributeStatus, User::STATUS_WAIT);
                }
                if(User::findByEmailActive($this->login)){
                    $this->addError($attribute, 'This email address has already been taken.');
                    $this->addError($attributeStatus, User::STATUS_ACTIVE);
                }
            }


            if($this->isPhone()){

                if(User::findByPhone($this->login)){
                    $this->addError($attribute, 'This phone number already exists, but not activated');
                    $this->addError($attributeStatus, User::STATUS_WAIT);
                }
                if(User::findByPhoneActive($this->login)){
                    $this->addError($attribute, 'This phone number already exists, but not activated');
                    $this->addError($attributeStatus, User::STATUS_WAIT);
                }

            }
        }
    }

    public function isEmail(): bool
    {
        return preg_match(LoginForm::emailPattern(), $this->login);
    }
    public function isPhone(): bool
    {

        return preg_match(LoginForm::phonePattern(),$this->login) && strlen($this->login) > 8 && strlen($this->login) <= 15;
    }

    public function setParams()
    {
        $this->phone = $this->isPhone() ? $this->login : null;
        $this->email = $this->isEmail() ? $this->login : null;
    }

    protected function internalForms(): array
    {
        return ['profile'];
    }

}