<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace box\tests\unit\entities\Generic\product;

use box\entities\generic\GenericProduct;
use Codeception\Test\Unit;

class GenericProductCreateTest extends Unit
{
    public function testSuccess()
    {
        $product = GenericProduct::create(
            $brandId = 1,
            $categoryId = 1,
            $name = 'Iphone 6',
            $description = 'Iphone 6 Description'
        );

        $this->assertEquals($brandId, 1);
        $this->assertEquals($categoryId, 1);
        $this->assertEquals($name, $product->name);
        $this->assertEquals($description, $product->description);
    }
}