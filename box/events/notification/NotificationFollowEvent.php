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

class NotificationFollowEvent extends Event
{
    public $from_id;
    public $to_id;
}