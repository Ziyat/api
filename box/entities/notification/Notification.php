<?php

namespace box\entities\notification;

use yii\db\ActiveRecord;

/**
 * @property integer $id
 * @property integer $type
 * @property integer $type_id
 * @property integer $from_id
 * @property integer $created_at
 *
 * @property NotificationAssignment[] $assignments
 *
 */
class Notification extends ActiveRecord
{
    const TYPE_NEW_FOLLOWER = 10;

    public static function create($type, $type_id, $from_id): self
    {
        $notification = new static();

        $notification->type = $type;
        $notification->type_id = $type_id;
        $notification->from_id = $from_id;
        $notification->created_at = time();

        return $notification;
    }

    public function setAssignment($notification_id, $to_id)
    {
        $assignments = $this->assignments;
        foreach ($assignments as $assignment) {
            if ($assignment->isForNotificationId($notification_id)) {
                return;
            }
        }
        $assignments[] = NotificationAssignment::create($to_id, $notification_id);
        $this->assignments = $assignments;
    }

    public function getAssignments()
    {
        return $this->hasMany(NotificationAssignment::class, ['id' => 'notification_id']);
    }

    ##########################

    public static function tableName()
    {
        return '{{%notifications}}';
    }
}