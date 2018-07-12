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

class MovementTest extends Unit
{
    public $service;

    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->service = new CategoryService(new CategoryRepository());
    }
    public function _fixtures()
    {
        return [
            'categories' => [
                'class' => CategoryFixture::class,
                'dataFile' => codecept_data_dir() . 'category.php'
            ]
        ];
    }

    public function testMoveUp()
    {

        $this->service->moveUp(3);

        $category = Category::findOne(3);

        $this->assertEquals($category->lft,2);

        $this->assertEquals($category->rgt,3);

    }
    public function testMoveDown()
    {

        $this->service->moveDown(2);

        $category = Category::findOne(2);

        $this->assertEquals($category->lft,4);

        $this->assertEquals($category->rgt,5);

    }
}