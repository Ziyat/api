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
use common\fixtures\shop\TagFixture;

class TagServiceEditTest extends Unit
{
    public function _fixtures()
    {
        return [
            'tags' => [
                'class' => TagFixture::class,
                'dataFile' => codecept_data_dir() . 'tag.php'
            ]
        ];
    }

    public $service;

    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->service = new TagService(new TagRepository());
    }

    public function testSuccess()
    {
        $tag = Tag::findOne(2);

        $form = new TagForm($tag);

        $form->name = 'name edit';
        $form->slug = 'name-edit';

        $this->service->edit($tag->id, $form);

        $tag = Tag::findOne(2);

        $this->assertEquals($tag->name, 'name edit');
        $this->assertEquals($tag->slug, 'name-edit');
    }
}