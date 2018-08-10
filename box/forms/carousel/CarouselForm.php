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
            $carousel->title = $title;
            $carousel->description = $description;
            $carousel->text = $text;
            $carousel->type = $type;
            $carousel->item_id = $item_id;
            $carousel->appointment = $appointment ?: self::APPOINTMENT_NEWS;
        }
        parent::__construct($config);
    }


    protected function internalForms(): array
    {
        return [''];
    }
}