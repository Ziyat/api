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
            $value = 'red',
            $price = 350,
            $main_photo_id = 1,
            $characteristic_id = 1,
            $quantity = 5
        );

        $this->assertEquals($modification->value, $value);
        $this->assertEquals($modification->price, $price);
    }
}