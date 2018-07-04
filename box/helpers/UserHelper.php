<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace box\helpers;


use box\entities\user\User;

class UserHelper
{
    public static function getStatus($status)
    {
        switch ($status){
            case User::STATUS_ACTIVE:
                return 'Activated';
            case User::STATUS_WAIT:
                return 'Wait';
            case User::STATUS_DELETED:
                return 'Deleted';
            default:
                return 'Unknown';
        }
    }
}