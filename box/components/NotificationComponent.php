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
use yii\helpers\VarDumper;

/**
 * @property Notification $notification
 * @property UserReadRepository $users
 * @property Client $client
 */
class NotificationComponent extends Component
{
    public $notification;
    public $users;
    public $client;

    public function __construct(array $config = [])
    {
        $this->notification = new Notification();
        $this->users = new UserReadRepository();
        $this->client = \Yii::$app->fireBase;

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

            foreach ($follower->pushTokens as $token) {
                $this->pushNotification($event->type, $event->type_id, $token->token);
            }
        }

        $notification->save();
    }

    /**
     * @param $type
     * @param $type_id
     * @param $token
     * @throws \InvalidArgumentException
     * @throws \UnexpectedValueException
     * @throws \yii\base\InvalidParamException
     */
    public function pushNotification($type, $type_id, $token)
    {
        $message = $this->pushMessage($type, $type_id);

        $note = $this->client->createNotification($message['title'], $message['body']);

        $note->setIcon('notification_icon_resource_name')
            ->setColor('#ffffff')
            ->setBadge(1);

        $message = $this->client->createMessage();
        $message->addRecipient(new Device($token));
        $message->setNotification($note)->setData([$type => $type_id]);

        $response = $this->client->send($message);
        $response->getStatusCode();
    }


    private function pushMessage($type, $type_id)
    {
        switch ($type) {
            case Notification::TYPE_NEW_PRODUCT:
                return [
                    'title' => 'User added new product',
                    'body' => 'product id: ' . $type_id,
                ];
            case Notification::TYPE_NEW_FOLLOWER:
                return [
                    'title' => 'New follower',
                    'body' => 'follower id: ' . $type_id,
                ];
            default:
                return [
                    'title' => 'Notification',
                    'body' => '',
                ];
        }
    }

}