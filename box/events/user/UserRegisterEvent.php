<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace box\events\user;


use box\entities\user\User;
use yii\base\Event;

/**
 * Class UserRegisterEvent
 * @property User $user
 * @property string $subject
 * @package box\events\user
 */

class UserRegisterEvent extends Event
{
    public $user;
    public $subject;
}