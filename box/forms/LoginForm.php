<?php

namespace box\forms;

use box\entities\Token;
use box\entities\User;
use function foo\func;
use Yii;
use yii\base\Model;

/**
 * Signin form
 * @property $email
 * @property $phone
 * @property $password
 */
class LoginForm extends Model
{
    public $email;
    public $password;
    public $phone;

    private $_user;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // password are both required
            [['password'], 'required'],
            [['email', 'phone'], 'required', 'when' => function($model){
                    return $this->phone === null && $this->email === null;
            }],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
            ['email', 'trim'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
        ];
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
                $this->addError($attribute, 'Incorrect email|phone or password.');
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
            $token->generateToken(time() + 3600 + 24);
            return $token->save() ? $token : null;
        }
        return null;

    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    private function getUser()
    {
        if ($this->_user === null) {
            $this->_user = $this->phone ? User::findByPhone($this->phone) : User::findByEmail($this->email);
        }

        return $this->_user;
    }
}
