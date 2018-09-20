<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace api;


use api\tests\ApiTester;
use common\fixtures\TokenFixture;
use common\fixtures\UserFixture;
use yii\helpers\VarDumper;

class ShippingCest
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
        ]);
    }

    public function access(ApiTester $I)
    {
        $I->sendPOST('/shop/shipping');
        $I->seeResponseCodeIs(401);
    }

    public function add(ApiTester $I)
    {
        $I->amBearerAuthenticated('token-correct');

        $I->sendPOST('/shop/shipping', [
            'name' => 'DHL',
            'description' => 'since 1908',
            'rates' => [
                [
                    'price_type' => 1,
                    'price_min' => 250.99,
                    'price_max' => 550.99,
                    'day_min' => 1,
                    'day_max' => 5,
                    'type' => 10,
                    'country_id' => 72
                ],
                [
                    'price_type' => 1,
                    'price_min' => 150.99,
                    'price_max' => 500.99,
                    'day_min' => 1,
                    'day_max' => 5,
                    'type' => 20,
                    'country_id' => 72
                ]
            ]
        ], [
            'photo' => codecept_data_dir('user/photos/photo1.jpg'),
        ]);

        $I->seeResponseCodeIs(201);
    }

    public function edit(ApiTester $I)
    {
        $I->amBearerAuthenticated('token-correct');

        $I->sendPOST('/shop/shipping/1',[
            'name'=>'fedEx',
            'rates' => [
                [
                    'id' => 1,
                    'price_type' => 10,
                    'price_min' => 8876.99,
                    'price_max' => 657.99,
                    'day_min' => 2,
                    'day_max' => 5,
                    'type' => 10,
                    'country_id' => 72
                ],
                [
                    'id' => 2,
                    'price_type' => 10,
                    'price_min' => 998.99,
                    'price_max' => 767.99,
                    'day_min' => 3,
                    'day_max' => 8,
                    'type' => 20,
                    'country_id' => 198
                ]
            ]
        ]);

        $I->seeResponseCodeIs(202);
    }

    public function view(ApiTester $I)
    {
        $I->amBearerAuthenticated('token-correct');
        $I->sendGET('/shop/shipping/1');
        $I->seeResponseCodeIs(200);
    }

    public function viewAll(ApiTester $I)
    {
        $I->amBearerAuthenticated('token-correct');
        $I->sendGET('/shop/shipping');
        $I->seeResponseCodeIs(200);
    }

    public function delete(ApiTester $I)
    {
        $I->amBearerAuthenticated('token-correct');
        $I->sendDELETE('/shop/shipping/1');
        $I->seeResponseCodeIs(204);
    }

    public function seeParams(ApiTester $I)
    {
        $I->amBearerAuthenticated('token-correct');
        $I->sendGET('/shop/shipping/params');
        $I->seeResponseCodeIs(201);
    }


}