<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace box\tests\unit\entities\Category;

use box\entities\Meta;
use box\entities\shop\Category;
use Codeception\Test\Unit;

class CategoryCreateTest extends Unit
{
    public function testSuccess()
    {
        $category = Category::create(
            $name = 'notebook', $slug = 'nout', $title = 'llss', $description = 'fdfsdf',
            new Meta($titleMeta = 'fsdfs',$descMeta ='fds',$keyMeta = 'fsdf')
        );

        $this->assertEquals($name, $category->name);
        $this->assertEquals($slug, $category->slug);
        $this->assertEquals($title, $category->title);
        $this->assertEquals($description, $category->description);

        $this->assertEquals($titleMeta, $category->meta->title);
        $this->assertEquals($descMeta, $category->meta->description);
        $this->assertEquals($keyMeta, $category->meta->keywords);
    }
}