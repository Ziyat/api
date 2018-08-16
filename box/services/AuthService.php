<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace box\services;

use box\entities\user\Token;
use box\entities\user\User;
use box\events\user\UserRegisterEvent;
use box\forms\auth\LoginForm;
use box\forms\auth\SetPasswordForm;
use box\repositories\TokenRepository;
use box\repositories\UserRepository;
use yii\helpers\VarDumper;

class AuthService
{
    private $users;
    private $event;
    private $transaction;
    private $tokens;
    private $token;

    public function __construct(
        UserRepository $repository,
        UserRegisterEvent $event,
        TransactionManager $transaction,
        TokenRepository $tokens
    )
    {
        $this->users = $repository;
        $this->event = $event;
        $this->transaction = $transaction;
        $this->tokens = $tokens;
        $this->token = new Token();
    }

    /**
     * @param LoginForm $form
     * @return Token
     * @throws \box\repositories\NotFoundException
     * @throws \yii\base\Exception
     */

    public function auth(LoginForm $form)
    {
        $user = $this->users->findByEmailAndPhone($form->login);

        return $this->generateToken($user);
    }

    /**
     * @param string $token
     * @return Token
     * @throws \box\repositories\NotFoundException
     * @throws \yii\base\Exception
     */
    public function activate(string $token)
    {
        $user = $this->users->findByActivateToken($token);

        $user->setActiveStatus();

        $user->removeActivateToken();

        $this->users->save($user);

        return $this->generateToken($user);
    }

    /**
     * @param $password_reset_token
     * @param SetPasswordForm $form
     * @return Token
     * @throws \box\repositories\NotFoundException
     * @throws \yii\base\Exception
     */
    public function setPassword($password_reset_token, SetPasswordForm $form): Token
    {
        $user = $this->users->findByPasswordResetToken($password_reset_token);
        $user->setPassword($form->password);
        $user->removePasswordResetToken();
        $this->users->save($user);

        return $this->generateToken($user);

    }

    /**
     * @param $refresherToken
     * @return array|Token|null|\yii\db\ActiveRecord
     * @throws \box\repositories\NotFoundException
     * @throws \yii\base\Exception
     */

    public function tokenRefresh($refresherToken)
    {
        /**
         * @var Token $token
         */

        $user = $this->users->findByAuthKey($refresherToken);
        $token = $user->tokens;
        if ($token->expired_at < time()) {
            return $this->generateToken($user);
        }
        return $token;
    }

    /**
     * @param User $user
     * @return Token
     * @throws \yii\base\Exception
     */
    protected function generateToken(User $user)
    {
        $this->token->user_id = $user->id;

        $this->token->generateToken(time() + 3600 * 24 * 7);

        $this->tokens->save($this->token);

        return $this->token;
    }
}