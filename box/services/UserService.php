<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace box\services;


use box\forms\user\UserEditForm;
use box\repositories\UserRepository;
use yii\helpers\VarDumper;

class UserService
{
    private $users;

    public function __construct(UserRepository $repository)
    {
        $this->users = $repository;
    }

    public function edit($id, UserEditForm $form)
    {
        // Profile Edit
        $profile = $this->users->findProfile($id);
        $profile->edit(
            $form->name,
            $form->lastName,
            $form->birthDate,
            $form->photo
        );

        // User Edit
        $user = $this->users->find($id);
        $user->edit(
            $form->email,
            $form->phone,
            $form->password,
            $profile
        );
        $this->users->save($user);

        return $user;
    }
}