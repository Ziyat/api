<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace box\forms\carousel;


use box\entities\carousel\Carousel;
use box\entities\carousel\Item;
use box\forms\CompositeForm;
use yii\base\Model;

/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 * Class CarouselForm
 * @package box\forms\carousel
 *
 * @property string $title
 * @property string $subTitle
 * @property integer $type
 * @property integer $template_id
 * @property integer $status
 */
class CarouselForm extends Model
{
    public $title;
    public $subTitle;
    public $type;
    public $template_id;
    public $status;

    public function __construct(Carousel $carousel = null, array $config = [])
    {
        if ($carousel) {
            $this->title = $carousel->title;
            $this->subTitle = $carousel->sub_title;
            $this->type = $carousel->type;
            $this->template_id = $carousel->template_id;
            $this->status = $carousel->status;
        }

        parent::__construct($config);
    }

    public function rules()
    {
        return [
            [['title', 'type', 'template_id'], 'required'],
            [['title', 'subTitle'], 'string'],
            [['template_id', 'status','type'], 'integer'],
        ];
    }
}