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
            $name = 'Name',
            $type = Characteristic::TYPE_INTEGER,
            $required = true,
            $default = 0,
            $variants = [4,5],
            $sort = 15
        );

        $this->assertEquals($name, $characteristic->name);
        $this->assertEquals($type, $characteristic->type);
        $this->assertEquals($required, $characteristic->required);
        $this->assertEquals($default, $characteristic->default);
        $this->assertEquals($variants, $characteristic->variants);
        $this->assertEquals($sort, $characteristic->sort);
        $this->assertTrue($characteristic->isSelect());
    }
}