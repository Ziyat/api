<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace box\tests\unit\services\Category;

use box\entities\shop\Category;
use box\forms\shop\CategoryForm;
use box\repositories\CategoryRepository;
use box\services\CategoryService;
use Codeception\Test\Unit;
use common\fixtures\shop\CategoryFixture;

class CategoryServiceEditTest extends Unit
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

        $name = 'notebook';

        $category = Category::findOne(2);

        $category->name = 'Edit Category';

        $form = new CategoryForm($category);

        $this->service->edit($category->id, $form);

        $this->assertNotEquals($category->name, $name);
    }
}