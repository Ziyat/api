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
    public function find($id): User
    {
        if(!$user = User::findOne($id)){
            throw new NotFoundException('User not found!');
        }
        return $user;
    }
    public function save(User $user)
    {
        if (!$user->save(false)) {
            throw new \DomainException('Save error');
        }
    }

    public function findProfile($id): Profile
    {
        if (!$profile = Profile::findOne(['user_id' => $id])) {
            throw new NotFoundException('Profile is not found.');
        }
        return $profile;
    }


    public function findByEmail($email): User
    {
        if (!$user = User::find()->where(['email' => $email])->active()->one()) {
            throw new NotFoundException('User is not found.');
        }
        return $user;
    }

    public function findByPasswordResetToken($token):User
    {
        if (!$user = User::find()->where(['password_reset_token' => $token])->active()->one()) {
            throw new NotFoundException('User is not found.');
        }
        return $user;
    }

}