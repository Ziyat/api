<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace box\forms\reviews;


use box\entities\review\Review;
use box\forms\CompositeForm;
use yii\base\Model;

/**
 * @property string $title
 * @property string $text
 * @property integer $type
 * @property integer $item_id
 * @property integer $score
 * @property integer $parentId
 *
 * @property PhotosForm $photos
 */
class ReviewForm extends CompositeForm
{
    public $title;
    public $text;
    public $type;
    public $item_id;
    public $score;

    public $parentId = 1;

    public function __construct(Review $review = null, array $config = [])
    {
        if ($review) {
            $this->title = $review->title;
            $this->text = $review->text;
            $this->type = $review->type;
            $this->item_id = $review->item_id;
            $this->score = $review->score;
            $this->parentId = $review->parent->id;
        }

        $this->photos = new PhotosForm();
        parent::__construct($config);
    }

    public function rules()
    {
        return [
            [['text', 'type', 'item_id'], 'required'],
            [['title','text'],'trim'],
            [['title','text'],'string'],
            [['type','item_id','score','parentId'],'integer'],
        ];
    }

    protected function internalForms(): array
    {
        return ['photos'];
    }
}