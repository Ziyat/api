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
        $type = Carousel::TYPE_GENERIC_PRODUCT;

        $carousel = Carousel::create($title, $subTitle, $type,1);
        $this->isTrue($carousel->save());
        $carouselDb = Carousel::findOne(['title' => $title]);
        $this->assertEquals($carouselDb->title, $title);
    }
}