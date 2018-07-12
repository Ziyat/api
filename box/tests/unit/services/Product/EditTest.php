<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace tests\unit\services\Product;

use box\entities\shop\product\Product;
use box\entities\user\User;
use box\repositories\BrandRepository;
use box\repositories\CategoryRepository;
use box\repositories\ProductRepository;
use box\repositories\TagRepository;
use box\services\ProductService;
use box\services\TransactionManager;
use Codeception\Test\Unit;
use common\fixtures\shop\BrandFixture;
use common\fixtures\shop\CategoryFixture;
use box\forms\shop\product\ProductCreateForm;
use common\fixtures\shop\TagFixture;
use yii\helpers\VarDumper;

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

        $name = 'rolex';
        $description = 'desc';
        $metaTitle = 'titleMeta';
        $metaDesc = 'descMeta';
        $metaKey = 'KeyMeta';



        $form = new ProductCreateForm();

        $form->brandId = 1;
        $form->categories->main = 1;
        $form->name = $name;
        $form->description = $description;
        $form->meta->title = $metaTitle;
        $form->meta->description = $metaDesc;
        $form->meta->keywords = $metaKey;
        $form->priceType = Product::PRICE_TYPE_FIX;

        $product = $this->service->create($form);

        $this->assertNotNull($product);
        $this->assertEquals($product->name, $name);
        $this->assertEquals($product->description, $description);
        $this->assertEquals($product->price_type, Product::PRICE_TYPE_FIX);

        $this->assertEquals($product->meta->title, $metaTitle);
        $this->assertEquals($product->meta->description, $metaDesc);
        $this->assertEquals($product->meta->keywords, $metaKey);

        $this->assertEquals($product->category_id, 1);
        $this->assertEquals($product->brand_id, 1);
    }


}