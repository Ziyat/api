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
use Codeception\Test\Unit;
use box\services\BrandService;

class BrandServiceCreateTest extends Unit
{
    public $service;

    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->service = new BrandService(new BrandRepository(),new ProductRepository());
    }

    public function testSuccess()
    {
        $brand = new Brand();
        $brand->name = 'name';
        $brand->slug = 'slugwwee';

        $form = new BrandForm($brand);

        $brandDb = $this->service->create($form);

        $brandDb = Brand::findOne($brandDb->id);

        $this->assertEquals($brandDb->name, $brand->name);
        $this->assertEquals(gettype($brandDb->id) == 'integer',true);
    }
}