<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace api\tests\api;


use api\tests\ApiTester;
use common\fixtures\ProfileFixture;
use common\fixtures\shop\CategoryFixture;
use common\fixtures\TokenFixture;
use common\fixtures\UserFixture;

class CharacteristicCest
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
            'category' => [
                'class' => CategoryFixture::class,
                'dataFile' => codecept_data_dir() . 'category.php'
            ]
        ]);
    }

    public function access(ApiTester $I)
    {
        $I->sendGET('/shop/characteristics');
        $I->seeResponseCodeIs(200);
    }

    public function authenticated(ApiTester $I)
    {
        $I->amBearerAuthenticated('token-correct');
        $I->sendGET('/shop/characteristics');
        $I->seeResponseCodeIs(200);
        $I->seeResponseContainsJson([]);
    }

    public function accessRole(ApiTester $I)
    {
        $I->amBearerAuthenticated('token-correct-id-2');
        $I->sendPOST('/shop/characteristics');
        $I->seeResponseCodeIs(403);
    }

    public function authenticatedRole(ApiTester $I)
    {
        $I->amBearerAuthenticated('token-correct');
        $I->sendPOST('/shop/characteristics');
        $I->seeResponseCodeIs(422);
    }

    public function createCharacteristic(ApiTester $I)
    {
        $I->amBearerAuthenticated('token-correct');
        $I->haveHttpHeader('Cache-Control', 'no-cache');
        $I->sendPOST('/shop/characteristics', [
            'name' => 'Color',
            'assignments' => [
                [
                    'category_id' => 2,
                    'variants' => ['red','blue'],
                ],
            ],
        ]);

        $I->seeResponseCodeIs(201);
        $I->seeResponseContainsJson([
            'name' => 'Color',
        ]);
    }

//    public function editCharacteristic(ApiTester $I)
//    {
//        $I->amBearerAuthenticated('token-correct');
//        $I->sendPOST('/shop/characteristics/1', [
//            'id' => 1,
//            'name' => 'metal',
//            'type' => 'string',
//            'sort' => 1,
//            'required' => 0,
//            'default' => null,
//            'variants_json' => '[]'
//        ]);
//        $I->seeResponseCodeIs(202);
//        $I->seeResponseContainsJson([
//            'name' => 'metal',
//            'type' => 'string',
//        ]);
//
//    }
//
//    public function viewCharacteristic(ApiTester $I)
//    {
//        $I->amBearerAuthenticated('token-correct');
//        $I->sendGET('/shop/characteristics/1');
//        $I->seeResponseCodeIs(200);
//        $I->seeResponseContainsJson([
//            'name' => 'metal',
//            'type' => 'string',
//        ]);
//
//    }

    public function deleteCharacteristic(ApiTester $I)
    {
        $I->amBearerAuthenticated('token-correct');
        $I->sendDELETE('/shop/characteristics/1');
        $I->seeResponseCodeIs(204);

    }
}