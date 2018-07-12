<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace box\tests\unit\services\Category;

use box\entities\shop\Category;
use box\repositories\CategoryRepository;
use box\services\CategoryService;
use Codeception\Test\Unit;
use common\fixtures\shop\CategoryFixture;

class RemoveTest extends Unit
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
        $category = Category::findOne(2);

        $this->service->remove($category->id);


        $this->assertEquals(Category::findOne($category->id),null);
    }
}