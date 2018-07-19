<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace box\tests\unit\entities\Modification;

use box\entities\Meta;
use Codeception\Test\Unit;
use box\entities\shop\product\Modification;

class ModificationEditTest extends Unit
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

        $modification->edit(
            3,
            'blue',
            200,
            2,
            2
        );


        $this->assertEquals($modification->value, 'blue');
        $this->assertEquals($modification->price, 200);

    }
}