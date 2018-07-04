<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace box\tests\unit\entities\Brand;

use box\entities\Meta;
use box\entities\shop\Brand;
use Codeception\Test\Unit;

class EditTest extends Unit
{
    public function testSuccess()
    {
        $brand = Brand::create(
            'Name',
            'slug',
            new Meta('Rolex', 'Schweiz', 'watch,test')
        );

        $metaTitle = 'Rolex 2';
        $metaDesc = 'Schweiz 2';
        $metaKeyword = 'watch,test 2';

        $brand->meta->title = $metaTitle;
        $brand->meta->description = $metaDesc;
        $brand->meta->keywords = $metaKeyword;

        $brand->edit($name = 'Name2', $slug = 'slug2', $brand->meta);

        $this->assertEquals($name, $brand->name);
        $this->assertEquals($slug, $brand->slug);
        $this->assertEquals($metaTitle, $brand->meta->title);
        $this->assertEquals($metaDesc, $brand->meta->description);
        $this->assertEquals($metaKeyword, $brand->meta->keywords);
    }
}