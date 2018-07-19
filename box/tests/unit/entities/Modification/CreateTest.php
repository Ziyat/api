<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace box\tests\unit\entities\Modification;

use box\entities\Meta;
use Codeception\Test\Unit;
use box\entities\shop\product\Modification;

class ModificationCreateTest extends Unit
{
    public function testSuccess()
    {
        $modification = Modification::create(
            $characteristic_id = 1,
            $value = 'red',
            $price = 350,
            $quantity = 5,
            $main_photo_id = 1
        );

        $this->assertEquals($modification->value, $value);
        $this->assertEquals($modification->price, $price);
    }
}