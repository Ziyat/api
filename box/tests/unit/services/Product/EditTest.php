<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace tests\unit\services\Product;

use box\entities\shop\product\Product;
use box\repositories\BrandRepository;
use box\repositories\CategoryRepository;
use box\repositories\ProductRepository;
use box\repositories\TagRepository;
use box\services\ProductService;
use box\services\TransactionManager;
use Codeception\Test\Unit;
use common\fixtures\shop\BrandFixture;
use common\fixtures\shop\CategoryFixture;
use common\fixtures\shop\product\assignments\CategoryFixture as CategoryAssignmentFixture;
use common\fixtures\shop\product\ProductFixture;
use common\fixtures\shop\TagFixture;

class ProductServiceEditTest extends Unit
{

    public function _fixtures()
    {
        return [
            'brands' => [
                'class' => BrandFixture::class,
                'dataFile' => codecept_data_dir() . 'brand.php'
            ],
            'categories' => [
                'class' => CategoryFixture::class,
                'dataFile' => codecept_data_dir() . 'category.php'
            ],
            'tags' => [
                'class' => TagFixture::class,
                'dataFile' => codecept_data_dir() . 'tag.php'
            ],
            'products' => [
                'class' => ProductFixture::class,
                'dataFile' => codecept_data_dir() . 'product/product.php'
            ],
            'category_assignments' => [
                'class' => CategoryAssignmentFixture::class,
                'dataFile' => codecept_data_dir() . 'product/categoryAssignment.php'
            ]
        ];
    }

    public $service;

    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->service = new ProductService(
            new ProductRepository(),
            new BrandRepository(),
            new CategoryRepository(),
            new TagRepository(),
            new TransactionManager()
        );
    }

    public function testSuccess()
    {
        $product = Product::findOne(1);

        $this->assertEquals($product->meta->title, 'metaTitle');

        $this->assertEquals($product->category->name, 'Notebook');

        foreach ($product->categories as $category)
        {
            $this->assertEquals($category->slug, 'notebook2');
        }

    }


}