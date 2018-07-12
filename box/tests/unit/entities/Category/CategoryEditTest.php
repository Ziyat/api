<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace box\tests\unit\entities\Category;

use box\entities\Meta;
use box\entities\shop\Category;
use Codeception\Test\Unit;

class CategoryEditTest extends Unit
{
    public function testSuccess()
    {
        $category = Category::create(
            'notebook', 'nout', 'llss', 'fdfsdf',
            new Meta($titleMeta = 'fsdfs',$descMeta ='fds',$keyMeta = 'fsdf')
        );

        $metaTitle = 'Rolex 2';
        $metaDesc = 'Schweiz 2';
        $metaKeyword = 'watch,test 2';

        $category->meta->title = $metaTitle;
        $category->meta->description = $metaDesc;
        $category->meta->keywords = $metaKeyword;

        $category->edit($name = 'Name2', $slug = 'slug2', $title = 'title', $description = 'desc', $category->meta);

        $this->assertEquals($name, $category->name);
        $this->assertEquals($slug, $category->slug);
        $this->assertEquals($title, $category->title);
        $this->assertEquals($description, $category->description);
        $this->assertEquals($metaTitle, $category->meta->title);
        $this->assertEquals($metaDesc, $category->meta->description);
        $this->assertEquals($metaKeyword, $category->meta->keywords);
    }
}