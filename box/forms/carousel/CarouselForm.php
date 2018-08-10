<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace box\forms\carousel;


use box\entities\carousel\Carousel;
use box\forms\CompositeForm;

class CarouselForm extends CompositeForm
{
    public $title;
    public $description;
    public $text;
    public $type;
    public $item_id;

    public function __construct(Carousel $carousel = null, array $config = [])
    {
        if($carousel){
            $this->title = $carousel->title;
            $this->description = $carousel->description;
            $this->text = $carousel->text;
            $this->type = $carousel->type;
            $this->item_id = $carousel->item_id;
        }
        parent::__construct($config);
    }


    protected function internalForms(): array
    {
        return [''];
    }
}