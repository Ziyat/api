<?php

namespace box\entities\notification;

use yii\db\ActiveRecord;

/**
 * @property integer $to_id
 * @property integer $notification_id
 * @property integer $status
 */
class NotificationAssignment extends ActiveRecord
{
    const STATUS_READ = 10;
    const STATUS_UNREAD = 20;

    public static function create($to_id, $notification_id): self
    {
        $assignment = new static();
        $assignment->to_id = $to_id;
        $assignment->notification_id = $notification_id;
        $assignment->status = self::STATUS_UNREAD;
        return $assignment;
    }

    public function read(): void
    {
        if (!$this->isRead()) {
            $this->status = self::STATUS_READ;
        }

        throw new \DomainException('status is all ready status read');
    }

    public function isRead(): bool
    {
        return $this->status == self::STATUS_READ;
    }

    public function isForNotificationId($notification_id): bool
    {
        return $this->notification_id == $notification_id;
    }

    ##########################

    public static function tableName()
    {
        return '{{%notifications}}';
    }
}