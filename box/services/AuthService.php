<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace box\services;

use box\entities\user\Token;
use box\events\user\UserRegisterEvent;
use box\forms\auth\LoginForm;
use box\repositories\TokenRepository;
use box\repositories\UserRepository;

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
     */

    public function auth(LoginForm $form)
    {
        $user = $this->users->findByEmailAndPhone($form->login);

        $this->token->user_id = $user->id;

        $this->token->generateToken(time() + 3600 + 24 * 7);

        $this->tokens->save($this->token);

        return $this->token;
    }

    /**
     * @param string $token
     * @return Token
     * @throws \box\repositories\NotFoundException
     */
    public function activate(string $token)
    {
        $user = $this->users->findByActivateToken($token);

        $user->setActiveStatus();

        $user->removeActivateToken();

        $this->users->save($user);

        $this->token->user_id = $user->id;

        $this->token->generateToken(time() + 3600 + 24 * 7);

        $this->tokens->save($this->token);

        return $this->token;
    }
}