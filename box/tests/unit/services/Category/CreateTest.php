<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace box\tests\unit\services\Category;

use box\entities\Meta;
use box\forms\shop\CategoryForm;
use box\repositories\CategoryRepository;
use box\services\CategoryService;
use Codeception\Test\Unit;
use common\fixtures\shop\CategoryFixture;

class CategoryServiceCreateTest extends Unit
{
    public function _fixtures()
    {
        return [
            'categories' => [
                'class' => CategoryFixture::class,
                'dataFile' => codecept_data_dir() . 'category.php'
            ]
        ];
    }

    public $service;

    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->service = new CategoryService(new CategoryRepository());
    }
    public function testSuccess()
    {
        $form = new CategoryForm();

        $form->name = 'name';
        $form->slug = 'slug';
        $form->title = 'title';
        $form->description = 'description';
        $form->parentId = 1;
        $form->meta = new Meta($titleMeta = 'titleMeta',$descMeta ='descMeta',$keyMeta = 'keyMeta');

        $category = $this->service->create($form);


        $this->assertEquals($category->name, $form->name);
        $this->assertEquals($category->slug, $form->slug);
        $this->assertEquals($category->title, $form->title);
        $this->assertEquals($category->description, $form->description);

        $this->assertEquals($titleMeta, $category->meta->title);
        $this->assertEquals($descMeta, $category->meta->description);
        $this->assertEquals($keyMeta, $category->meta->keywords);
    }
}