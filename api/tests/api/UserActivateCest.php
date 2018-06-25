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

    public function badMethod(ApiTester $I)
    {
        $I->sendPOST('/activate/223344');

        $I->seeResponseCodeIs(405);
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
        $I->sendPOST('/signup',[
            'login' => 'user_activate@test.com',
            'password' => 'password_0'
        ]);

        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseJsonMatchesJsonPath('$.activate_token');
        $I->seeResponseJsonMatchesJsonPath('$.created_at');

        $activate_code = $I->grabDataFromResponseByJsonPath('$.activate_token');

        $I->sendGET('/activate/'.$activate_code[0]);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseJsonMatchesJsonPath('$.token');
        $I->seeResponseJsonMatchesJsonPath('$.expired');

    }

    public function successByPhone(ApiTester $I)
    {
        $I->sendPOST('/signup',[
            'login' => 998974457010,
            'password' => 'fdfdsfsdfs'
        ]);

        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseJsonMatchesJsonPath('$.activate_token');
        $I->seeResponseJsonMatchesJsonPath('$.created_at');

        $activate_code = $I->grabDataFromResponseByJsonPath('$.activate_token');

        $I->sendGET('/activate/'.$activate_code[0]);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseJsonMatchesJsonPath('$.token');
        $I->seeResponseJsonMatchesJsonPath('$.expired');
    }



}
