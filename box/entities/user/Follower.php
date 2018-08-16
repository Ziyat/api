<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace box\entities\user;

use yii\db\ActiveRecord;

/**
 * Followers model
 * @property integer $user_id
 * @property integer $follower_id
 * @property integer $created_at
 * @property integer $status
 *
 * @property User $following
 * @property User $follower
 */

class Follower extends ActiveRecord
{
    const APPROVE = 1;
    const NOT_APPROVE = 0;

    public static function create($follow_id, $status): self
    {
        $follower = new static();
        $follower->user_id = $follow_id;
        $follower->status = $status;
        $follower->created_at = time();
        return $follower;
    }

    public function isFollower($user_id): bool
    {
       return $this->user_id === $user_id;
    }

    public function getFollowing()
    {
        return $this->hasOne(User::class,['id' => 'user_id']);
    }

    public function getFollower()
    {
        return $this->hasOne(User::class,['id' => 'follower_id']);
    }

    public static function tableName(): string
    {
        return '{{%followers}}';
    }


}