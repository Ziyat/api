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
use yii\web\Response;

/**
 * Class ReviewCest
 */
class ReviewCest
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
        $I->sendPOST('/reviews',[
            'title' => 'first review',
            'text' => 'first review text',
            'type' => 10,
            'item_id' => 1,
        ]);
        $I->seeResponseCodeIs(200);
    }

    public function edit(ApiTester $I)
    {

        $I->amBearerAuthenticated('token-correct');
        $I->sendPOST('/reviews/2',[
            'title' => 'first review edit',
            'text' => 'first review text edit',
            'type' => 10,
            'item_id' => 1,
        ]);

        $I->seeResponseCodeIs(200);
    }

    public function addChild(ApiTester $I)
    {

        $I->amBearerAuthenticated('token-correct');
        $I->sendPOST('/reviews',[
            'title' => 'child review',
            'text' => 'child review text',
            'type' => 10,
            'item_id' => 1,
            'parentId' => 2,
        ]);
        $I->seeResponseCodeIs(200);
    }

    public function viewChildren(ApiTester $I)
    {
        $I->amBearerAuthenticated('token-correct');
        $I->sendGET('/reviews/2/children');
        $I->seeResponseCodeIs(200);
    }

    public function viewParent(ApiTester $I)
    {
        $I->amBearerAuthenticated('token-correct');
        $I->sendGET('/reviews/3/parent');
        $I->seeResponseCodeIs(200);
    }

    public function viewParents(ApiTester $I)
    {
        $I->amBearerAuthenticated('token-correct');
        $I->sendGET('/reviews/3/parents');
        $I->seeResponseCodeIs(200);
    }



    public function getAllByTypeAndItemId(ApiTester $I)
    {

        $I->amBearerAuthenticated('token-correct');
        $I->sendGET('/reviews/10/1');
        $I->seeResponseCodeIs(200);
    }




    public function accessRemove(ApiTester $I)
    {

        $I->amBearerAuthenticated('token-correct');
        $I->sendDELETE('/reviews/1');

        $I->seeResponseCodeIs(400);
    }

    public function remove(ApiTester $I)
    {

        $I->amBearerAuthenticated('token-correct');
        $I->sendDELETE('/reviews/2');
        $I->seeResponseCodeIs(200);
    }

}
