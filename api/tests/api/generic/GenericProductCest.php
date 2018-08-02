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

class GenericProductCest
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
        $I->sendGET('/generic/products');
        $I->seeResponseCodeIs(200);
    }

    public function authenticated(ApiTester $I)
    {
        $I->amBearerAuthenticated('token-correct');
        $I->sendGET('/generic/products');
        $I->seeResponseCodeIs(200);
        $I->seeResponseContainsJson([]);
    }


    public function createViaDataFile(ApiTester $I)
    {

        $I->amBearerAuthenticated('token-correct');
        $I->sendPOST(
            '/generic/products',
            [],
            [
                'files' => [
                    codecept_data_dir('user/photos/photo1.jpg'),
                    codecept_data_dir('user/photos/photo2.jpg'),
                ],
                'data' =>
                    codecept_data_dir('generic/productData.json'),
            ]
        );

        $I->seeResponseCodeIs(201);
    }

    public function createWithDataWithoutAFile(ApiTester $I)
    {

        $I->amBearerAuthenticated('token-correct');
        $I->sendPOST(
            '/generic/products',
            [
                "brandId" => 1,
                "name" => "rolex",
                "description" => "watch valt",
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
                    ],
                    [
                        "value" => "0978",
                        "characteristic_id" => 1,
                    ]
                ],
                "tags" => [
                    "existing" => [],
                    "textNew" => "newtag,tagWatch,WatchValt"
                ],
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
}