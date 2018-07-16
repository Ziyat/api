<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace box\tests\unit\services\Brand;

use box\entities\shop\Brand;
use box\forms\shop\BrandForm;
use box\repositories\BrandRepository;
use box\repositories\ProductRepository;
use box\services\BrandService;
use Codeception\Test\Unit;
use common\fixtures\shop\BrandFixture;

class BrandServiceRemoveTest extends Unit
{
    public function _fixtures()
    {
        return [
            'brands' => [
                'class' => BrandFixture::class,
                'dataFile' => codecept_data_dir() . 'brand.php'
            ]
        ];
    }
    public $service;

    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->service = new BrandService(new BrandRepository(),new ProductRepository);
    }

    public function testSuccess()
    {
        $brand = Brand::findOne(2);

        $this->service->remove($brand->id);

        $brandFind = Brand::findOne($brand->id);

        $this->assertNull($brandFind);
    }
}