<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace repositories\notification;


use box\entities\notification\Notification;
use box\repositories\NotFoundException;

class NotificationRepository
{
    /**
     * @param $id
     * @return Notification|null
     * @throws NotFoundException
     */
    public function get($id): Notification
    {
        if (!$notification = Notification::findOne($id)) {
            throw new NotFoundException('Notification is not found.');
        }
        return $notification;
    }

    public function save(Notification $notification)
    {
        if (!$notification->save()) {
            throw new \RuntimeException('Saving error.');
        }
    }

    /**
     * @param Notification $notification
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function remove(Notification $notification)
    {
        if (!$notification->delete()) {
            throw new \RuntimeException('Removing error.');
        }
    }
}