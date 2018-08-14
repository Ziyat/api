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
        $I->seeResponseCodeIs(422);
    }

    public function accessRole(ApiTester $I)
    {
        $I->amBearerAuthenticated('token-correct-id-2');
        $I->sendPOST('/carousels');
        $I->seeResponseCodeIs(403);
    }

    public function authenticatedRole(ApiTester $I)
    {
        $I->amBearerAuthenticated('token-correct');
        $I->sendPOST('/carousels');
        $I->seeResponseCodeIs(422);
    }

    public function createCarousel(ApiTester $I)
    {
        $I->amBearerAuthenticated('token-correct');
        $I->haveHttpHeader('Cache-Control', 'no-cache');
        $I->sendPOST('/carousels', [
            'title' => 'name',
            'type' => 1,
            'item_id' => 1,
        ], [
            'files' => [
                codecept_data_dir('user/photos/photo1.jpg'),
                codecept_data_dir('user/photos/photo2.jpg'),
            ]
        ]);

        $I->seeResponseCodeIs(201);
        $I->seeResponseContainsJson([
            'title' => 'name',
            'type' => 'user_product',
            'item_id' => 1,
        ]);
    }

    public function updateCarousel(ApiTester $I)
    {
        $I->amBearerAuthenticated('token-correct');
        $I->haveHttpHeader('Cache-Control', 'no-cache');
        $I->sendPOST('/carousels/1', [
            'title' => 'update',
            'type' => 2,
            'item_id' => 3
        ]);

        $I->seeResponseCodeIs(202);
        $I->seeResponseContainsJson([
            'title' => 'update',
            'type' => 'brand',
            'item_id' => 3
        ]);
    }

    public function viewCarousel(ApiTester $I)
    {
        $I->amBearerAuthenticated('token-correct');
        $I->haveHttpHeader('Cache-Control', 'no-cache');
        $I->sendGET('/carousels/1');

        $I->seeResponseCodeIs(200);
        $I->seeResponseContainsJson([
            'title' => 'update',
            'type' => 'brand',
            'item_id' => 3
        ]);
    }

    public function Carousels(ApiTester $I)
    {
        $I->amBearerAuthenticated('token-correct');
        $I->haveHttpHeader('Cache-Control', 'no-cache');
        $I->sendGET('/carousels');
        $I->seeResponseCodeIs(200);
        $I->seeResponseContainsJson([
            [
                'title' => 'update',
                'type' => 'brand',
                'item_id' => 3
            ]
        ]);
    }

    public function deleteCarousels(ApiTester $I)
    {
        $I->amBearerAuthenticated('token-correct');
        $I->haveHttpHeader('Cache-Control', 'no-cache');
        $I->sendDELETE('/carousels/1');
        $I->seeResponseCodeIs(204);
    }
}