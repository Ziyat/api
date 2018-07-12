<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace box\tests\unit\services\Brand;

use box\forms\shop\BrandForm;
use box\repositories\BrandRepository;
use Codeception\Test\Unit;
use box\entities\shop\Brand;
use box\services\BrandService;
use common\fixtures\shop\BrandFixture;

class BrandServiceEditTest extends Unit
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
        $this->service = new BrandService(new BrandRepository());
    }

    public function testSuccess()
    {
        $brand = Brand::findOne(1);

        $form = new BrandForm($brand);

        $form->name = 'name edit';

        $this->service->edit($brand->id, $form);

        $brand = Brand::findOne(1);

        $this->assertEquals($brand->name, 'name edit');
    }
}