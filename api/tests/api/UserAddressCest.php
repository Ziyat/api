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
        VarDumper::dump($I->grabResponse());
//        $I->seeResponseCodeIs(201);
//        $I->seeResponseContainsJson([
//            'id' => 1
//        ]);
    }
}