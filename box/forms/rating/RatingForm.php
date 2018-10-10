<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace box\forms\rating;


use box\entities\rating\Rating;
use yii\base\Model;

/**
 * @property integer $type
 * @property integer $item_id
 * @property float $score
 * @property string $name
 *
 * @property Rating $_rating
 */
class RatingForm extends Model
{
    public $type;
    public $item_id;
    public $score;
    public $name;

    public $_rating;

    public function __construct(Rating $rating = null, array $config = [])
    {
        if ($rating) {

            $this->type = $rating->type;
            $this->item_id = $rating->item_id;
            $this->score = $rating->score;
            $this->name = $rating->name;

            $this->_rating = $rating;
        }
        parent::__construct($config);
    }

    public function rules()
    {
        return [
            [['type', 'item_id', 'name'], 'required'],
            ['name', 'string'],
            [['type', 'item_id'], 'integer'],
            [['score'], 'double', 'min' => 0, 'max' => 5],
        ];
    }
}