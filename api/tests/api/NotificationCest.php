<?php

namespace api\tests\functional;

use api\tests\ApiTester;
use common\fixtures\notification\AssignmentFixture;
use common\fixtures\notification\NotificationFixture;
use common\fixtures\ProfileFixture;
use common\fixtures\shop\BrandFixture;
use common\fixtures\shop\CategoryFixture;
use common\fixtures\shop\CharacteristicFixture;
use common\fixtures\shop\product\ProductFixture;
use common\fixtures\TokenFixture;
use common\fixtures\UserFixture;
use yii\helpers\VarDumper;

/**
 * Class ReviewCest
 */
class NotificationCest
{

    public function _before(ApiTester $I)
    {
        $I->haveFixtures([
            'user' => [
                'class' => UserFixture::class,
                'dataFile' => codecept_data_dir() . 'user.php'
            ],
            'token' => [
                'class' => TokenFixture::class,
                'dataFile' => codecept_data_dir() . 'token.php'
            ],
            'profile' => [
                'class' => ProfileFixture::class,
                'dataFile' => codecept_data_dir() . 'profile.php'
            ],
            'characteristic' => [
                'class' => CharacteristicFixture::class,
                'dataFile' => codecept_data_dir() . 'characteristic.php'
            ],
            'brand' => [
                'class' => BrandFixture::class,
                'dataFile' => codecept_data_dir() . 'brand.php'
            ],
            'category' => [
                'class' => CategoryFixture::class,
                'dataFile' => codecept_data_dir() . 'category.php'
            ],
            'product' => [
                'class' => ProductFixture::class,
                'dataFile' => codecept_data_dir() . 'user/product.php'
            ],
            'notification' => [
                'class' => NotificationFixture::class,
                'dataFile' => codecept_data_dir() . 'notification.php'
            ],
            'notificationAssignments' => [
                'class' => AssignmentFixture::class,
                'dataFile' => codecept_data_dir() . 'notificationAssignments.php'
            ],
        ]);
    }

    public function new(ApiTester $I)
    {
        $I->amBearerAuthenticated('token-correct-id-2');
        $I->sendGET('/notification/new');
    }

    public function all(ApiTester $I)
    {
        $I->amBearerAuthenticated('token-correct-id-2');
        $I->sendGET('/notification/all');
    }

    public function addPushToken(ApiTester $I)
    {
        $I->amBearerAuthenticated('token-correct-id-2');
        $I->sendPost('/notification/push-token',[
            'token' => 'bla-bla',
            'service' => 'fireBase',
        ]);
    }

}
