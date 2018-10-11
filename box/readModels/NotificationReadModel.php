<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace box\readModels;

use box\entities\notification\Notification;
use box\entities\notification\NotificationAssignment;
use box\repositories\NotFoundException;

class NotificationReadModel
{
    /**
     * @param $id
     * @return array|\yii\db\ActiveRecord[]
     * @throws NotFoundException
     */
    public function findNew($id)
    {
        if (!$rating = Notification::find()
            ->joinWith(['assignments' => function ($q) use ($id) {
                $q->andWhere(['=', 'to_id', $id])->andWhere(['=', 'status', NotificationAssignment::STATUS_UNREAD]);
            }])->all()) {
            throw new NotFoundException('Notification is not found.');
        }
        return $rating;
    }

    /**
     * @param $id
     * @return array|\yii\db\ActiveRecord[]
     * @throws NotFoundException
     */
    public function findAll($id)
    {
        if (!$rating = Notification::find()
            ->joinWith(['assignments' => function ($q) use ($id) {
                $q->andWhere(['=', 'to_id', $id]);
            }])->all()) {
            throw new NotFoundException('Notification is not found.');
        }
        return $rating;
    }
}