<?php

namespace box\entities\notification;

use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
use yii\db\ActiveQuery;
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
    const TYPE_NEW_PRODUCT = 15;

    public static function create($type, $type_id, $from_id): self
    {
        $notification = new static();

        $notification->type = $type;
        $notification->type_id = $type_id;
        $notification->from_id = $from_id;
        $notification->created_at = time();

        return $notification;
    }

    public function assign($to_id)
    {
        $assignments = $this->assignments;
        foreach ($assignments as $assignment) {
            if ($assignment->isForNotificationId($this->id)) {
                return;
            }
        }
        $assignments[] = NotificationAssignment::create($to_id);
        $this->assignments = $assignments;
    }

    public function getAssignments(): ActiveQuery
    {
        return $this->hasMany(NotificationAssignment::class, ['notification_id' => 'id']);
    }

    public function fields()
    {
        return [
            'id' => 'id',
            'type' => 'type',
            'type_id' => 'type_id',
            'from_id' => 'from_id',
            'created_at' => 'created_at',
            'assignments' => function(){
                return $this->assignments;
            },
        ];
    }

    public function behaviors()
    {
        return [
            [
                'class' => SaveRelationsBehavior::class,
                'relations' => ['assignments']
            ]
        ];
    }

    ##########################

    public static function tableName()
    {
        return '{{%notifications}}';
    }
}