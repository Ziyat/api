<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace box\repositories;


use box\entities\user\Profile;
use box\entities\user\User;

class UserRepository
{
    /**
     * @param $id
     * @return User
     * @throws NotFoundException
     */
    public function find($id): User
    {
        if(!$user = User::findOne($id)){
            throw new NotFoundException('User not found!');
        }
        return $user;
    }

    /**
     * @param User $user
     */
    public function save(User $user)
    {
        if (!$user->save(false)) {
            throw new \DomainException('Save error');
        }
    }

    /**
     * @param $id
     * @return Profile
     * @throws NotFoundException
     */
    public function findProfile($id): Profile
    {
        if (!$profile = Profile::findOne(['user_id' => $id])) {
            throw new NotFoundException('Profile is not found.');
        }
        return $profile;
    }

    /**
     * @param $email
     * @return User|array
     * @throws NotFoundException
     */
    public function findByEmail($email): User
    {
        if (!$user = User::find()->andWhere(['email' => $email])->active()->one()) {
            throw new NotFoundException('User not found.');
        }
        return $user;
    }

    /**
     * @param $login
     * @return User|array
     * @throws NotFoundException
     */
    public function findByEmailAndPhone($login): User
    {
        if (!$user = User::find()
            ->andWhere(['=', 'email', $login])
            ->orWhere(['=', 'phone', $login])
            ->active()
            ->one()
        ) {
            throw new NotFoundException('User not found.');
        }
        return $user;
    }

    /**
     * @param $token
     * @return User|array
     * @throws NotFoundException
     */
    public function findByPasswordResetToken($token):User
    {
        if (!$user = User::find()->andWhere(['password_reset_token' => $token])->active()->one()) {
            throw new NotFoundException('User not found.');
        }
        return $user;
    }

    /**
     * @param $token
     * @return User|array
     * @throws NotFoundException
     */
    public function findByActivateToken($token): User
    {
        if (!$user = User::find()->andWhere(['activate_token' => $token])->one()) {
            throw new NotFoundException('User not found.');
        }
        return $user;
    }

}