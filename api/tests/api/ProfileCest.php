<?php

namespace api\tests\functional;

use api\tests\ApiTester;
use common\fixtures\ProfileFixture;
use common\fixtures\TokenFixture;
use common\fixtures\UserFixture;
use yii\helpers\VarDumper;
use yii\web\Response;

/**
 * Class ProfileCest
 */
class ProfileCest
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

    public function expired(ApiTester $I)
    {
        $I->amBearerAuthenticated('token-expired');
        $I->sendGET('/profile');
        $I->seeResponseCodeIs(401);
        $I->seeResponseContainsJson([
            'message' => 'token expired'
        ]);

    }

    public function refreshToken(ApiTester $I)
    {
        $I->sendPATCH('/token-refresh/refresherToken');
        $I->seeResponseCodeIs(205);

    }

    public function editData(ApiTester $I)
    {

        $I->amBearerAuthenticated('token-correct');
        $I->sendPOST('/profile/edit', [
            "name" => "Ziyatilla"
        ]);
        $I->seeResponseCodeIs(200);
        $I->seeResponseContainsJson([
            'name' => 'Ziyatilla'
        ]);
    }

}
