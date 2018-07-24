<?php

namespace api\tests\functional;

use api\tests\ApiTester;
use common\fixtures\TokenFixture;
use common\fixtures\UserFixture;

/**
 * Class ActivateUserCest
 */
class UserActivateCest
{

    public function _before(ApiTester $I)
    {
        $I->haveFixtures([
            'user' => [
                'class' => UserFixture::class,
                'dataFile' => codecept_data_dir() . 'user.php'
            ],
        ]);
    }

    public function badMethod(ApiTester $I)
    {
        $I->sendPOST('/activate/223344');

        $I->seeResponseCodeIs(404);
        $I->seeResponseIsJson();
    }

    public function wrongToken(ApiTester $I)
    {
        $I->sendGET('/activate/223344_6456456');

        $I->seeResponseCodeIs(404);
        $I->seeResponseContainsJson([
            'field' => 'User',
            'message' => 'User not found.'
        ]);
    }



    public function successByEmail(ApiTester $I)
    {
        $I->sendGET('/activate/223355');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseJsonMatchesJsonPath('$.token');
        $I->seeResponseJsonMatchesJsonPath('$.expired');

    }

    public function successByPhone(ApiTester $I)
    {
        $I->sendGET('/activate/223355');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseJsonMatchesJsonPath('$.token');
        $I->seeResponseJsonMatchesJsonPath('$.expired');
    }
}
