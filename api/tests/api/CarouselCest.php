<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace api\tests\api;


use api\tests\ApiTester;
use box\entities\carousel\Carousel;
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
        $I->amBearerAuthenticated('token-correct-id-3');
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
            'title' => 'title',
            'subTitle' => 'subTitle',
            'type' => Carousel::TYPE_GENERIC_PRODUCT,
            'template_id' => 1
        ]);
        $I->seeResponseCodeIs(201);
        $I->seeResponseContainsJson([
            'title' => 'title',
            'sub_title' => 'subTitle',
            'type' => Carousel::TYPE_GENERIC_PRODUCT,
            'template_id' => 1,
        ]);
    }

    public function updateCarousel(ApiTester $I)
    {
        $I->amBearerAuthenticated('token-correct');
        $I->haveHttpHeader('Cache-Control', 'no-cache');
        $I->sendPOST('/carousels/1', [
            'title' => 'title Edit',
            'subTitle' => 'subTitle Edit',
            'type' => Carousel::TYPE_GENERIC_PRODUCT,
            'template_id' => 2,
            'status' => 1,
        ]);

        $I->seeResponseCodeIs(202);
        $I->seeResponseContainsJson([
            'title' => 'title Edit',
            'sub_title' => 'subTitle Edit',
            'type' => Carousel::TYPE_GENERIC_PRODUCT,
            'template_id' => 2,
            'status' => 1,
        ]);
    }

    public function viewCarousel(ApiTester $I)
    {
        $I->amBearerAuthenticated('token-correct');
        $I->haveHttpHeader('Cache-Control', 'no-cache');
        $I->sendGET('/carousels/1');

        $I->seeResponseCodeIs(200);
        $I->seeResponseContainsJson([
            'title' => 'title Edit',
            'sub_title' => 'subTitle Edit',
            'type' => Carousel::TYPE_GENERIC_PRODUCT,
            'template_id' => 2
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
                'id' => 1,
                'title' => 'title Edit',
                'sub_title' => 'subTitle Edit',
                'type' => Carousel::TYPE_GENERIC_PRODUCT,
                'template_id' => 2
            ]
        ]);
    }


    public function CarouselAddItem(ApiTester $I)
    {
        $I->amBearerAuthenticated('token-correct');
        $I->haveHttpHeader('Cache-Control', 'no-cache');
        $I->sendPOST('/carousels/1/items', [
            'title' => 'Item 1 Carousel 1 Title',
            'description' => 'Item 1 Carousel 1 Desc',
            'text' => 'Item 1 Carousel 1 Text',
            'item_id' => 1
        ], [
            'files' => [
                codecept_data_dir('user/photos/photo1.jpg'),
                codecept_data_dir('user/photos/photo2.jpg'),
            ]
        ]);
        $I->seeResponseCodeIs(201);
    }

    public function CarouselEditItem(ApiTester $I)
    {
        $I->amBearerAuthenticated('token-correct');
        $I->haveHttpHeader('Cache-Control', 'no-cache');
        $I->sendPOST('/carousels/1/items/1', [
            'title' => 'Item 1 Carousel 1 Title Edit',
            'description' => 'Item 1 Carousel 1 Desc Edit',
            'text' => 'Item 1 Carousel 1 Text Edit',
            'item_id' => 1
        ]);
        $I->seeResponseCodeIs(202);
    }

    public function CarouselViewItem(ApiTester $I)
    {
        $I->amBearerAuthenticated('token-correct');
        $I->haveHttpHeader('Cache-Control', 'no-cache');
        $I->sendGET('/carousels/1/items/1');
        $I->seeResponseCodeIs(200);
        $I->seeResponseContainsJson([
            'title' => 'Item 1 Carousel 1 Title Edit'
        ]);
    }

    public function CarouselAddItemImages(ApiTester $I)
    {
        $I->amBearerAuthenticated('token-correct');
        $I->haveHttpHeader('Cache-Control', 'no-cache');
        $I->sendPOST('/carousels/1/items/1/images', [], [
            'files' => [
                codecept_data_dir('carousel/image1.JPG'),
                codecept_data_dir('carousel/image2.JPG'),
            ]
        ]);
        $I->seeResponseCodeIs(201);

    }

    public function CarouselDeleteItemImage(ApiTester $I)
    {
        $I->amBearerAuthenticated('token-correct');
        $I->haveHttpHeader('Cache-Control', 'no-cache');
        $I->sendDELETE('/carousels/1/items/1/images/3');
        $I->seeResponseCodeIs(204);
    }

    public function CarouselDeleteItem(ApiTester $I)
    {
        $I->amBearerAuthenticated('token-correct');
        $I->haveHttpHeader('Cache-Control', 'no-cache');
        $I->sendDELETE('/carousels/1/items/1');
        $I->seeResponseCodeIs(204);
    }



    public function deleteCarousels(ApiTester $I)
    {
        $I->amBearerAuthenticated('token-correct');
        $I->haveHttpHeader('Cache-Control', 'no-cache');
        $I->sendDELETE('/carousels/1');
        $I->seeResponseCodeIs(204);
    }
}