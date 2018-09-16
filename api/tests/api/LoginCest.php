<?php

namespace api\tests\functional;

use api\tests\ApiTester;
use common\fixtures\ProfileFixture;
use common\fixtures\TokenFixture;
use common\fixtures\UserFixture;
use yii\helpers\VarDumper;

/**
 * Class LoginCest
 */
class LoginCest
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



    public function badMethod(ApiTester $I)
    {
        $I->sendGET('/login');
        $I->seeResponseCodeIs(404);
        $I->seeResponseIsJson();
    }

    public function wrongPassword(ApiTester $I)
    {
        $I->sendPOST('/login',[
                'login' => 'tests@mail.com',
                'password' => 'wrong-password'
            ]);

        $I->seeResponseCodeIs(422);
        $I->seeResponseContainsJson([
            'field' => 'password',
            'message' => 'Incorrect email / phone or password.'
        ]);
    }

    public function emptyEmailAnPhone(ApiTester $I)
    {
        $I->sendPOST('/login',[
                'password' => 'wrong-password'
            ]);

        $I->seeResponseCodeIs(422);
        $I->seeResponseContainsJson([
            'field' => 'login',
            'message' => 'Login cannot be blank.'
        ]);
    }

    public function successLoginByEmail(ApiTester $I)
    {
        $I->sendPOST('/login', [
                'login' => 'tests@mail.com',
                'password' => 'password_0'
            ]);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseJsonMatchesJsonPath('$.token');
        $I->seeResponseJsonMatchesJsonPath('$.expired');
    }

    public function successLoginByPhone(ApiTester $I)
    {
        $I->sendPOST('/login', [
                'login' => 998974457089,
                'password' => 'password_0'
            ]);

        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseJsonMatchesJsonPath('$.token');
        $I->seeResponseJsonMatchesJsonPath('$.expired');
    }


    public function successSetPassword(ApiTester $I)
    {
        $I->sendPOST('/forgot/set-password/334455',[
            'password' => 555666888
        ]);
        $I->seeResponseCodeIs(205);
        $I->seeResponseIsJson();
        $I->seeResponseJsonMatchesJsonPath('$.token');
        $I->seeResponseJsonMatchesJsonPath('$.expired');
    }

    public function successForgot(ApiTester $I)
    {
        $I->sendPOST('/forgot', [
                'email' => 'tests@mail.com',
            ]);
        $I->seeResponseCodeIs(200);
    }


    public function ckeckToken(ApiTester $I)
    {
        $I->sendPATCH('/check-token/token-expired');
        echo PHP_EOL;
        VarDumper::dump($I->grabResponse());
        echo PHP_EOL;
    }


}
