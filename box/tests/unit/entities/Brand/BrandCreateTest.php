<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace box\tests\unit\entities\Brand;

use box\entities\Meta;
use box\entities\shop\Brand;
use Codeception\Test\Unit;

class BrandCreateTest extends Unit
{
    public function testSuccess()
    {
        $brand = Brand::create(
            $name = 'Name',
            $slug = 'slug',
            $photo = null,
            new Meta(
                $metaTitle = 'Rolex',
                $metaDesc ='Schweiz',
                $metaKeyword = 'watch,test'
            )
        );

        $this->assertEquals($name, $brand->name);
        $this->assertEquals($slug, $brand->slug);
        $this->assertEquals($metaTitle, $brand->meta->title);
        $this->assertEquals($metaDesc, $brand->meta->description);
        $this->assertEquals($metaKeyword, $brand->meta->keywords);
    }
}