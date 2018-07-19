<?php

namespace box\forms\auth;

use box\entities\user\Token;
use box\entities\user\User;
use Yii;
use yii\base\Model;

/**
 * Login form
 * @property $login
 * @property $password
 */
class LoginForm extends Model
{

    public $login;
    public $password;

    private $_user;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // password are both required
            [['password', 'login'], 'required'],

            ['login', 'trim'],

            // login is validated by validateLogin()
            ['login', 'validateLogin'],

            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }

    /**
     * Validates the login.
     * This method serves as the inline validation for login.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validateLogin($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $attributeStatus = $this->generateAttributeLabel('status');

            if (!$this->isPhone() && !$this->isEmail()) {
                $this->addError($attribute, 'Please enter a valid mobile number or email address.');
            }

            if ($this->isEmail() && User::findByEmail($this->login)) {
                $this->addError($attribute, 'This email address already exists, but not activated');
                $this->addError($attributeStatus, User::STATUS_WAIT);
            }

            if ($this->isPhone() && User::findByPhone($this->login)) {
                $this->addError($attribute, 'This phone number already exists, but not activated');
                $this->addError($attributeStatus, User::STATUS_WAIT);

            }
        }
    }

    private function isEmail()
    {
        return preg_match(self::emailPattern(), strtolower($this->login));
    }

    private function isPhone()
    {

        return preg_match(self::phonePattern(), strtolower($this->login)) && strlen($this->login) > 8 && strlen($this->login) <= 15;
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Incorrect email / phone or password.');
            }
        }
    }

    /**
     * @return Token|null
     */
    public function auth()
    {
        if ($this->validate()) {
            $token = new Token();
            $token->user_id = $this->getUser()->id;
            $token->generateToken(time() + ((3600 + 24) * 7));
            return $token->save() ? $token : null;
        }
        return null;

    }

    public static function login(User $user)
    {
        $token = new Token();
        if ($user) {
            $token->user_id = $user->id;
            $token->generateToken(time() + ((3600 + 24) * 7));
        }
        return $token->save() ? $token : null;
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    private function getUser()
    {
        if ($this->_user === null) {
            $this->_user = $this->isPhone() ? User::findByPhoneActive($this->login) : User::findByEmailActive($this->login);
        }
        return $this->_user;
    }

    public static function emailPattern()
    {
        return '/^(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){255,})(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){65,}@)(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22))(?:\.(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22)))*@(?:(?:(?!.*[^.]{64,})(?:(?:(?:xn--)?[a-z0-9]+(?:-[a-z0-9]+)*\.){1,126}){1,}(?:(?:[a-z][a-z0-9]*)|(?:(?:xn--)[a-z0-9]+))(?:-[a-z0-9]+)*)|(?:\[(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){7})|(?:(?!(?:.*[a-f0-9][:\]]){7,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?)))|(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){5}:)|(?:(?!(?:.*[a-f0-9]:){5,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3}:)?)))?(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))(?:\.(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))){3}))\]))$/';
    }

    public static function phonePattern()
    {
        return '/^[0-9]+$/';
    }
}
