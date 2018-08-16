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

class BrandCest
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
        $I->sendGET('/shop/brands');
        $I->seeResponseCodeIs(200);
    }

    public function authenticated(ApiTester $I)
    {
        $I->amBearerAuthenticated('token-correct');
        $I->sendGET('/shop/brands');
        $I->seeResponseCodeIs(200);
        $I->seeResponseContainsJson([]);
    }

    public function accessRole(ApiTester $I)
    {
        $I->amBearerAuthenticated('token-correct-id-3');
        $I->sendPOST('/shop/brands');
        $I->seeResponseCodeIs(403);
    }

    public function authenticatedRole(ApiTester $I)
    {
        $I->amBearerAuthenticated('token-correct');
        $I->sendPOST('/shop/brands');
        $I->seeResponseCodeIs(422);
    }

    public function createBrand(ApiTester $I)
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

    public function editBrand(ApiTester $I)
    {
        $I->amBearerAuthenticated('token-correct');
        $I->sendPOST('/shop/brands/1', [
            'name' => 'name2',
            'slug' => 'slug2',
        ]);
        $I->seeResponseCodeIs(202);
        $I->seeResponseContainsJson([
            'name' => 'name2',
            'slug' => 'slug2'
        ]);

    }

    public function viewBrand(ApiTester $I)
    {
        $I->amBearerAuthenticated('token-correct');
        $I->sendGET('/shop/brands/1');
        $I->seeResponseCodeIs(200);
        $I->seeResponseContainsJson([
            'name' => 'name2',
            'slug' => 'slug2'
        ]);

    }

    public function deleteBrand(ApiTester $I)
    {
        $I->amBearerAuthenticated('token-correct');
        $I->sendDELETE('/shop/brands/1');
        $I->seeResponseCodeIs(204);

    }
}