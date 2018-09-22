<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace box\tests\unit\entities\Shipping;

use box\entities\shop\product\ShippingAssignment;
use box\entities\shop\shipping\ShippingService;
use Codeception\Test\Unit;
use common\fixtures\shop\BrandFixture;
use common\fixtures\shop\CategoryFixture;
use common\fixtures\shop\product\assignments\CategoryFixture as CategoryAssignmentFixture;
use common\fixtures\shop\CharacteristicFixture;
use common\fixtures\shop\product\ModificationFixture;
use common\fixtures\shop\product\PhotoFixture;
use common\fixtures\shop\product\PriceFixture;
use common\fixtures\shop\product\ProductFixture;
use common\fixtures\shop\TagFixture;
use yii\helpers\VarDumper;

class ServiceCreateTest extends Unit
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
                'class' => ProductFixture::class,
                'dataFile' => codecept_data_dir() . 'product/product.php'
            ],
            'category_assignments' => [
                'class' => CategoryAssignmentFixture::class,
                'dataFile' => codecept_data_dir() . 'product/categoryAssignment.php'
            ],
            'prices' => [
                'class' => PriceFixture::class,
                'dataFile' => codecept_data_dir() . 'product/price.php'
            ],
            'photo' => [
                'class' => PhotoFixture::class,
                'dataFile' => codecept_data_dir() . 'product/photo.php'
            ],
            'modification' => [
                'class' => ModificationFixture::class,
                'dataFile' => codecept_data_dir() . 'product/modification.php'
            ]
        ];
    }

    public function testCreateAssignment()
    {
        $shippingService = ShippingService::create(
            'DHL',
            null,
            null
        );
        $shippingService->save();
        $shippingService->setRate(
            null,
            1,
            null,
            null,
            null,
            null,
            null,
            1,
            1
        );
        $shippingService->save();
        $assignment = ShippingAssignment::create(
            $shippingService->shippingServiceRates[0]->id,
            [1, 4, 5, 6, 8],
            0,
            356.7
        );

        $assignment->save();
        $assignment = ShippingAssignment::findOne(['rate_id' => $shippingService->shippingServiceRates[0]->id]);

    }
}