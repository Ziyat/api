<?php

namespace common\components;

use box\entities\user\User;
use Yii;
use yii\rbac\Assignment;
use yii\rbac\PhpManager;


class AuthManager extends PhpManager
{

    public function getAssignments($userId)
    {
        if ($userId && $user = $this->getUser($userId)) {
            $assignment = new Assignment();
            $assignment->userId = $userId;
            $assignment->roleName = $user->role;
            return [$assignment->roleName => $assignment];
        }
        return [];
    }

    public function getAssignment($roleName, $userId)
    {
        if ($userId && $user = $this->getUser($userId)) {
            if ($user->role == $roleName) {
                $assignment = new Assignment();
                $assignment->userId = $userId;
                $assignment->roleName = $user->role;
                return $assignment;
            }
        }
        return null;
    }

    public function assign($role, $userId)
    {
        if ($userId && $user = $this->getUser($userId)) {
            $this->setRole($user, $role->name);
        }
    }

    public function revoke($role, $userId)
    {
        if ($userId && $user = $this->getUser($userId)) {
            if ($user->role == $role->name) {
                $this->setRole($user, null);
            }
        }
    }

    public function revokeAll($userId)
    {
        if ($userId && $user = $this->getUser($userId)) {
            $this->setRole($user, null);
        }
    }

    private function getUser($userId)
    {
        if (!Yii::$app->user->isGuest && Yii::$app->user->id == $userId) {
            return Yii::$app->user->identity;
        } else {
            return User::findOne($userId);
        }
    }

    private function setRole(User $user, $roleName)
    {
        $user->role = $roleName;
        $user->updateAttributes(['role' => $roleName]);
    }

}
