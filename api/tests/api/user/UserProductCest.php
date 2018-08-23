<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace api\tests\api\user;


use api\tests\ApiTester;
use common\fixtures\ProfileFixture;
use common\fixtures\shop\BrandFixture;
use common\fixtures\shop\CategoryFixture;
use common\fixtures\shop\CharacteristicFixture;
use common\fixtures\TokenFixture;
use common\fixtures\UserFixture;
use yii\helpers\VarDumper;

class UserProductCest
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
        ]);
    }

    public function access(ApiTester $I)
    {
        $I->sendGET('/user/products');
        $I->seeResponseCodeIs(401);
    }

    public function authenticated(ApiTester $I)
    {
        $I->amBearerAuthenticated('token-correct');
        $I->sendGET('/shop/products');
        $I->seeResponseCodeIs(200);
        $I->seeResponseContainsJson([]);
    }


    public function createViaDataFile(ApiTester $I)
    {

        $I->amBearerAuthenticated('token-correct');
        $I->sendPOST(
            '/user/products',
            [],
            [
                'files' => [
                    codecept_data_dir('user/photos/photo1.jpg'),
                    codecept_data_dir('user/photos/photo2.jpg'),
                ],
                'data' =>
                    codecept_data_dir('user/productData.json'),
            ]
        );

        $I->seeResponseCodeIs(201);
    }

    public function createWithDataWithoutAFile(ApiTester $I)
    {

        $I->amBearerAuthenticated('token-correct');
        $I->sendPOST(
            '/user/products',
            [
                "brandId" => 1,
                "name" => "rolex",
                "description" => "watch valt",
                "priceType" => "fix",
                "quantity" => 3,
                "condition" => 'used',
                "categories" => [
                    "main" => 2,
                    "others" => []
                ],
                "characteristics" => [
                    [
                        "value" => "1336",
                        "id" => "2"
                    ],
                    [
                        "value" => "XL",
                        "id" => "1"
                    ]
                ],
                "modifications" => [
                    [
                        "value" => "1336iu",
                        "characteristic_id" => 2,
                        "quantity" => 5,
                        "price" => 992
                    ],
                    [
                        "value" => "0978",
                        "characteristic_id" => 1,
                        "quantity" => 9,
                        "price" => 100
                    ]
                ],
                "tags" => [
                    "existing" => [],
                    "textNew" => "newtag,tagWatch,WatchValt"
                ],
                "meta" => [
                    "title" => "Meta title",
                    "description" => "Meta Desc",
                    "keywords" => "watch valt company, watch sales"
                ],
                "price" => [
                    "current" => 22.66,
                    "max" => 20,
                    "end" => 10,
                ]
            ],
            [
                'files' => [
                    codecept_data_dir('user/photos/photo1.jpg'),
                    codecept_data_dir('user/photos/photo2.jpg'),
                ]
            ]
        );

        $I->seeResponseCodeIs(201);
    }


    public function editWithDataWithoutAFile(ApiTester $I)
    {
        $I->amBearerAuthenticated('token-correct');
        $I->sendPOST(
            '/user/products/2',
            [
                "brandId" => 1,
                "name" => "rolex2",
                "description" => "watch valt",
                "priceType" => "fix",
                "quantity" => 3,
                "categories" => [
                    "main" => 2,
                    "others" => []
                ],
                "characteristics" => [
                    [
                        "value" => "1336",
                        "id" => "2"
                    ]
                ],
                "modifications" => [
                    [
                        "id" => 1,
                        "value" => "1334",
                        "characteristic_id" => 2,
                        "quantity" => 10,
                        "price" => 550
                    ]
                ],
                "tags" => [
                    "existing" => [],
                    "textNew" => "newtag,tagWatch,WatchValt"
                ],
                "meta" => [
                    "title" => "Meta title",
                    "description" => "Meta Desc",
                    "keywords" => "watch valt company, watch sales"
                ],
                "price" => [
                    "current" => 22.66,
                    "max" => 20,
                    "end" => 10,
                ]
            ],
            [
                'files' => [
                    codecept_data_dir('user/photos/photo1.jpg'),
                    codecept_data_dir('user/photos/photo2.jpg'),
                ]
            ]
        );
        $I->seeResponseCodeIs(202);
    }
}