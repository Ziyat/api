<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace box\tests\unit\services\Tag;

use box\forms\shop\TagForm;
use box\repositories\TagRepository;
use Codeception\Test\Unit;
use box\entities\shop\Tag;
use box\services\TagService;

class TagServiceCreateTest extends Unit
{
    public $service;

    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->service = new TagService(new TagRepository());
    }

    public function testSuccess()
    {
        $tag = new Tag();
        $tag->name = 'name';
        $tag->slug = 'slug';

        $form = new TagForm($tag);

        $tagDb = $this->service->create($form);

        $tagDb = Tag::findOne($tagDb->id);

        $this->assertEquals($tagDb->name, $tag->name);
        $this->assertEquals(gettype($tagDb->id) == 'integer',true);
    }
}