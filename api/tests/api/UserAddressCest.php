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

class UserAddressCest
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
        $I->sendPOST('/user/addresses');
        $I->seeResponseCodeIs(401);
    }

    public function add(ApiTester $I)
    {
        $I->amBearerAuthenticated('token-correct');

        $I->sendPOST('/user/addresses',[
            'name' => 'Mirkhanov Ziyodilla Saparovich',
            'country_id' => 79,
            'address_line_1' => 'Kim Pen Xva, J.Asimov, 10',
            'city' => 'Tashkent',
            'index' => '100500',
        ]);

        $I->seeResponseCodeIs(201);
        $I->seeResponseContainsJson([
            'id' => 1
        ]);
    }

    public function edit(ApiTester $I)
    {
        $I->amBearerAuthenticated('token-correct');

        $I->sendPOST('/user/addresses/1',[
            'address_line_1' => 'Yong\'ichqo\'li, Istiqlol, 10',
            'default' => 0,
        ]);
        $I->seeResponseCodeIs(202);
    }

    public function remove(ApiTester $I)
    {
        $I->amBearerAuthenticated('token-correct');
        $I->sendDELETE('/user/addresses/1');
        $I->seeResponseCodeIs(204);
    }
}