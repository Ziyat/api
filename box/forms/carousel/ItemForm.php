<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace box\forms\carousel;


use box\entities\carousel\Item;
use box\forms\CompositeForm;

/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 * Class ItemForm
 * @package box\forms\carousel
 * @property string $title
 * @property string $description
 * @property string $text
 * @property integer $item_id
 *
 * @property ImageForm $images
 */
class ItemForm extends CompositeForm
{
    public $title;
    public $description;
    public $text;
    public $item_id;

    public function __construct(Item $item = null, array $config = [])
    {
        if ($item) {
            $this->title = $item->title;
            $this->description = $item->description;
            $this->text = $item->text;
            $this->item_id = $item->item_id;
        }
        $this->images = new ImageForm();


        parent::__construct($config);
    }

    public function rules()
    {
        return [
            [['title', 'item_id'], 'required'],
            [['title', 'description', 'text'], 'string'],
            [['item_id'], 'integer'],
        ];
    }


    protected function internalForms(): array
    {
        return ['images'];
    }
}