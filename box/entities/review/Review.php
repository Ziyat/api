<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace box\entities\review;

use box\entities\review\queries\ReviewsQuery;
use paulzi\nestedsets\NestedSetsBehavior;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * @property integer $id
 * @property string $title
 * @property string $text
 * @property integer $type
 * @property integer $item_id
 * @property integer $score
 * @property integer $lft
 * @property integer $rgt
 * @property integer $depth
 * @property integer $created_at
 * @property integer $created_by
 * @property integer $updated_at
 * @property integer $updated_by
 *
 * @property Review $parent
 * @property Review[] $parents
 * @property Review[] $children
 * @property Review $prev
 * @property Review $next
 *
 * @mixin NestedSetsBehavior
 */
class Review extends ActiveRecord
{
    public const TYPE_USER_PRODUCT = 10;
    public const TYPE_GENERIC_PRODUCT = 15;

    public static function create($title, $text, $type, $item_id, $score): self
    {
        $review = new static();
        $review->title = $title;
        $review->text = $text;
        $review->type = $type;
        $review->item_id = $item_id;
        $review->score = $score;

        return $review;
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::class,
            BlameableBehavior::class,
            NestedSetsBehavior::class,
        ];
    }

    public static function tableName()
    {
        return '{{%reviews}}';
    }

    public function transactions(): array
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    public static function find()
    {
        return new ReviewsQuery(static::class);
    }
}