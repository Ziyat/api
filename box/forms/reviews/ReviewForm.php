<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace box\forms\reviews;


use yii\base\Model;

/**
 * @property string $title
 * @property string $text
 * @property integer $type
 * @property integer $item_id
 * @property integer $score
 */

class ReviewForm extends Model
{
    public $title;
    public $text;
    public $type;
    public $item_id;
    public $score;

    public $parentId = 1;

    public function rules()
    {
        return [
            [['title','text'],'trim'],
            [['title','text'],'string'],
            [['type','item_id','score','parentId'],'integer'],
        ];
    }
}