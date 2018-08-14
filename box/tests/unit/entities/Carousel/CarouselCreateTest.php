<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace box\tests\unit\entities\Carousel;

use box\entities\carousel\Carousel;
use Codeception\Test\Unit;

class CarouselCreateTest extends Unit
{
    public function testSuccess()
    {
        $title = 'Title';
        $subTitle = 'subTitle';
        $description = 'description';
        $text = 'Text';
        $type = Carousel::TYPE_GENERIC_PRODUCT;
        $item_id = 1;
        $appointment = Carousel::APPOINTMENT_NEWS;

        $carousel = Carousel::create(
            $title,
            $subTitle,
            $description,
            $text,
            $type,
            $item_id,
            $appointment
        );
        $this->isTrue($carousel->save());
        $carouselDb = Carousel::findOne(['text' => $text]);
        $this->assertEquals($carouselDb->text, $text);
    }
}