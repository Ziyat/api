<?php

namespace api\tests\functional;

use api\tests\ApiTester;
use common\fixtures\ProfileFixture;
use common\fixtures\shop\BrandFixture;
use common\fixtures\shop\CategoryFixture;
use common\fixtures\shop\CharacteristicFixture;
use common\fixtures\shop\product\ProductFixture;
use common\fixtures\TokenFixture;
use common\fixtures\UserFixture;
use yii\helpers\VarDumper;

/**
 * Class RatingCest
 */
class RatingCest
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
        ]);
    }

    public function access(ApiTester $I)
    {
        $I->sendGET('/profile');
        $I->seeResponseCodeIs(401);
    }

    public function authenticated(ApiTester $I)
    {
        $I->amBearerAuthenticated('token-correct');
        $I->sendGET('/profile');
        $I->seeResponseCodeIs(200);
        $I->seeResponseContainsJson([
            'id' => 1
        ]);
    }


    public function add(ApiTester $I)
    {

        $I->amBearerAuthenticated('token-correct');
        $I->sendPOST('/ratings', [
            'type' => 10,
            'item_id' => 1,
            'score' => 4,
            'name' => 'do you not like this product',
        ]);

        $I->sendPOST('/ratings', [
            'type' => 10,
            'item_id' => 1,
            'score' => 5,
            'name' => 'do you like this product',
        ]);

        $I->seeResponseCodeIs(200);
    }

    public function edit(ApiTester $I)
    {
        $I->amBearerAuthenticated('token-correct');
        $I->sendPOST('/ratings/1', [
            'type' => 10,
            'item_id' => 1,
            'score' => 4.5,
        ]);

        $I->seeResponseCodeIs(200);
    }

    public function middleRatingUserProduct(ApiTester $I)
    {
        $I->amBearerAuthenticated('token-correct');
        $I->sendGET('/public/user/products/1');
        $I->seeResponseCodeIs(200);
        $I->seeResponseContainsJson([
            'rating' => 4.75
        ]);
    }


}
