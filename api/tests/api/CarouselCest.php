<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace api\tests\api;


use api\tests\ApiTester;
use common\fixtures\ProfileFixture;
use common\fixtures\TokenFixture;
use common\fixtures\UserFixture;
use yii\helpers\VarDumper;

class CarouselCest
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
            ]
        ]);
    }

    public function access(ApiTester $I)
    {
        $I->sendPOST('/carousels');
        $I->seeResponseCodeIs(401);

    }

    public function authenticated(ApiTester $I)
    {
        $I->amBearerAuthenticated('token-correct');
        $I->sendPOST('/carousels');
        VarDumper::dump($I->grabResponse());
//        $I->seeResponseCodeIs(200);
//        $I->seeResponseContainsJson([]);
    }

    public function accessRole(ApiTester $I)
    {
        $I->amBearerAuthenticated('token-correct-id-2');
        $I->sendPOST('/shop/brands');
        $I->seeResponseCodeIs(403);
    }

    public function authenticatedRole(ApiTester $I)
    {
        $I->amBearerAuthenticated('token-correct');
        $I->sendPOST('/shop/brands');
        $I->seeResponseCodeIs(422);
    }

    public function createCarousel(ApiTester $I)
    {
        $I->amBearerAuthenticated('token-correct');
        $I->haveHttpHeader('Cache-Control', 'no-cache');
        $I->sendPOST('/shop/brands', [
            'name' => 'name',
            'slug' => 'slug',
        ]);

        $I->seeResponseCodeIs(201);
        $I->seeResponseContainsJson([
            'name' => 'name',
            'slug' => 'slug'
        ]);
    }
}