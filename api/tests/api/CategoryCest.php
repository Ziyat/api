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

class CategoryCest
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
        $I->sendGET('/shop/categories');
        $I->seeResponseCodeIs(200);
    }

    public function authenticated(ApiTester $I)
    {
        $I->amBearerAuthenticated('token-correct');
        $I->sendGET('/shop/categories');
        $I->seeResponseCodeIs(200);
        $I->seeResponseContainsJson([]);
    }

    public function accessRole(ApiTester $I)
    {
        $I->amBearerAuthenticated('token-correct-id-3');
        $I->sendPOST('/shop/categories');
        $I->seeResponseCodeIs(403);
    }

    public function authenticatedRole(ApiTester $I)
    {
        $I->amBearerAuthenticated('token-correct');
        $I->sendPOST('/shop/categories');
        $I->seeResponseCodeIs(422);
    }


    public function createCategory(ApiTester $I)
    {
        $I->amBearerAuthenticated('token-correct');
        $I->haveHttpHeader('Cache-Control', 'no-cache');
        $I->sendPOST('/shop/categories', [
            'name' => 'name',
            'slug' => 'slug',
            'parentId' => 1,
        ]);

        $I->seeResponseCodeIs(201);
        $I->seeResponseContainsJson([
            'name' => 'name',
            'slug' => 'slug'
        ]);
    }

    public function editCategory(ApiTester $I)
    {
        $I->amBearerAuthenticated('token-correct');
        $I->sendPOST('/shop/categories/2', [
            'name' => 'name2',
            'slug' => 'slug2',
        ]);
        $I->seeResponseCodeIs(202);
        $I->seeResponseContainsJson([
            'name' => 'name2',
            'slug' => 'slug2'
        ]);

    }

    public function viewCategory(ApiTester $I)
    {
        $I->amBearerAuthenticated('token-correct');
        $I->sendGET('/shop/categories/2');
        $I->seeResponseCodeIs(200);
        $I->seeResponseContainsJson([
            'name' => 'name2',
            'slug' => 'slug2'
        ]);

    }

    public function deleteCategory(ApiTester $I)
    {
        $I->amBearerAuthenticated('token-correct');
        $I->sendDELETE('/shop/categories/2');
        $I->seeResponseCodeIs(204);

    }
}