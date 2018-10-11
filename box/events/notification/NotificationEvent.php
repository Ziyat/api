<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace box\events\notification;


use box\entities\user\User;
use yii\base\Event;

/**
 * Class UserRegisterEvent
 * @property User $user
 * @property string $subject
 * @package box\events\user
 * @property $from_id
 * @property $to_id
 * @property $type
 * @property $type_id
 */

class NotificationEvent extends Event
{
    public $from_id;
    public $to_id;
    public $type;
    public $type_id;
}