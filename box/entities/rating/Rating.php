<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace box\entities\rating;

use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * @property integer $id
 * @property integer $type
 * @property integer $item_id
 * @property integer $score
 * @property integer $created_at
 * @property integer $created_by
 * @property integer $updated_at
 * @property integer $updated_by
 * @property string $name
 *
 */

class Rating extends ActiveRecord
{
    public const TYPE_USER_PRODUCT = 10;
    public const TYPE_GENERIC_PRODUCT = 15;
    public const TYPE_USER = 20;

    public static function create($type, $item_id, $score, $name): self
    {
        $rating = new static();
        $rating->type = $type;
        $rating->item_id = $item_id;
        $rating->name = $name;
        $rating->score = $score ?? 0;

        return $rating;
    }

    public function edit($type, $item_id, $score, $name): void
    {
        $this->type = $type;
        $this->item_id = $item_id;
        $this->score = $score;
        $this->name = $name;
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::class,
            BlameableBehavior::class,
        ];
    }

    public static function tableName()
    {
        return '{{%ratings}}';
    }

    public function transactions(): array
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }
}