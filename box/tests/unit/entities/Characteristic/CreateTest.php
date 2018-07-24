<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace box\tests\unit\entities\Characteristic;

use box\entities\Meta;
use box\entities\shop\Brand;
use box\entities\shop\Characteristic;
use Codeception\Test\Unit;

class CharacteristicCreateTest extends Unit
{
    public function testSuccess()
    {
        $characteristic = Characteristic::create(
            $name = 'Name'
        );

        $this->assertEquals($name, $characteristic->name);
    }
}