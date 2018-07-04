<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace box\tests\unit\entities\Tag;

use Codeception\Test\Unit;
use box\entities\shop\Tag;

class CreateTest extends Unit
{
    public function testSuccess()
    {
        $tag = Tag::create(
            $name = 'Name',
            $slug = 'slug'
        );

        $this->assertEquals($name, $tag->name);
        $this->assertEquals($slug, $tag->slug);
    }
}