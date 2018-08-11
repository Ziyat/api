<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace tests\unit\services\Generic\product;

use box\entities\generic\GenericProduct;
use box\repositories\generic\ProductRepository;
use box\services\generic\ProductService;
use box\services\TransactionManager;
use box\repositories\BrandRepository;
use box\repositories\CategoryRepository;
use box\repositories\TagRepository;
use Codeception\Test\Unit;
use common\fixtures\generic\product\GenericModificationFixture;
use common\fixtures\generic\product\GenericPhotoFixture;
use common\fixtures\generic\product\GenericProductFixture;
use common\fixtures\shop\BrandFixture;
use common\fixtures\shop\CategoryFixture;
use common\fixtures\shop\CharacteristicFixture;
use common\fixtures\generic\product\assignments\GenericCategoryFixture;
use common\fixtures\shop\TagFixture;


class GenericProductServiceEditTest extends Unit
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
            'characteristic' => [
                'class' => CharacteristicFixture::class,
                'dataFile' => codecept_data_dir() . 'characteristic.php'
            ],
            'products' => [
                'class' => GenericProductFixture::class,
                'dataFile' => codecept_data_dir() . 'generic/product.php'
            ],
            'category_assignments' => [
                'class' => GenericCategoryFixture::class,
                'dataFile' => codecept_data_dir() . 'generic/categoryAssignment.php'
            ],
            'photo' => [
                'class' => GenericPhotoFixture::class,
                'dataFile' => codecept_data_dir() . 'generic/photo.php'
            ],
            'modification' => [
                'class' => GenericModificationFixture::class,
                'dataFile' => codecept_data_dir() . 'generic/modification.php'
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

    public function testChangeMainPhotoModificationSuccess()
    {

        $product = GenericProduct::findOne(1);
        $product = $this->service->setModificationPhoto($product->id,$product->modifications[0]->id, 1);
        $this->assertEquals($product->modifications[0]->main_photo_id , 1);

    }
}