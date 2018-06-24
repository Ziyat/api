<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace box\repositories;


use box\entities\User;

class UserRepository
{
    public function save(User $user): void
    {
        if (!$user->save()) {
            throw new \DomainException('save error');
        }
    }
}