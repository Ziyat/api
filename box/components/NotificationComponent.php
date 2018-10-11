<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace box\components;


use box\entities\notification\Notification;
use box\entities\user\User;
use box\events\notification\NotificationEvent;
use box\readModels\UserReadRepository;
use yii\base\Component;
use yii\helpers\VarDumper;

/**
 * @property Notification $notification
 * @property UserReadRepository $users
 */
class NotificationComponent extends Component
{
    public $notification;
    public $users;

    public function __construct(array $config = [])
    {
        $this->notification = new Notification();
        $this->users = new UserReadRepository();
        parent::__construct($config);
    }

    /**
     * @param NotificationEvent $event
     */
    public function newProduct(NotificationEvent $event)
    {
        $notification = $this->notification::create($event->type, $event->type_id, $event->from_id);
        $user = User::findOne($event->from_id);
        foreach ($user->followers as $follower) {
            $notification->assign($follower->id);
        }
        $notification->save();
    }
}