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
use paragraph1\phpFCM\Recipient\Device;
use understeam\fcm\Client;
use yii\base\Component;

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
     * @throws \InvalidArgumentException
     * @throws \UnexpectedValueException
     * @throws \yii\base\InvalidParamException
     */
    public function newProduct(NotificationEvent $event)
    {
        $notification = $this->notification::create($event->type, $event->type_id, $event->from_id);
        $user = User::findOne($event->from_id);
        foreach ($user->followers as $follower) {
            $notification->assign($follower->id);
        }

        $notification->save();

        $this->push('user added new product', 'product id: ' . $event->type_id, $event->type, $event->type_id);
    }

    /**
     * @param $title
     * @param $body
     * @param $type
     * @param $type_id
     * @throws \InvalidArgumentException
     * @throws \UnexpectedValueException
     * @throws \yii\base\InvalidParamException
     */
    public function push($title, $body, $type, $type_id)
    {
        /**
         * @var Client $fireBase
         */

        $fireBase = \Yii::$app->fireBase;
        $note = $fireBase->createNotification($title, $body);
        $note->setIcon('notification_icon_resource_name')
            ->setColor('#ffffff')
            ->setBadge(1);

        $message = $fireBase->createMessage();
        $message->addRecipient(new Device('dnLTAj98vdM:APA91bGu09-CjSZ-GloPKmQafMEyWRDigkTYybXBNddCZQBo5aqY5YklKH16IQ19Tfi1k0v96xy4jFWlLw6B-VR9mjQDW75gIeulqo9Ebt6Xi8J4z26nX31kpwi7_uFGb7q8W57DnOWB'));
        $message->setNotification($note)
            ->setData([$type => $type_id]);

        $response = $fireBase->send($message);
        $response->getStatusCode();
    }

}