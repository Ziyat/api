<?php

namespace api\tests\functional;

use api\tests\ApiTester;
use common\fixtures\TokenFixture;
use common\fixtures\UserFixture;

/**
 * Class SignupCest
 */
class SignupCest
{
    public function badMethod(ApiTester $I)
    {
        $I->sendGET('/signup');
        $I->seeResponseCodeIs(404);
        $I->seeResponseIsJson();
    }

    public function emptyParams(ApiTester $I)
    {
        $I->sendPOST('/signup');
        $I->seeResponseCodeIs(422);
        $I->seeResponseContainsJson([
            'field' => 'login',
            'message' => 'Login cannot be blank.'
        ]);
    }

    public function emptyPassword(ApiTester $I)
    {
        $I->sendPOST('/signup', [
            'phone' => 998974457018
        ]);
        $I->seeResponseCodeIs(422);
        $I->seeResponseContainsJson([
            'field' => 'password',
            'message' => 'Password cannot be blank.'
        ]);
    }

    public function wrongEmail(ApiTester $I)
    {
        $I->sendPOST('/signup', [
            'login' => 'gsdgsdg@ffasfsadf432',
            'password' => 'fdfdsfsdfs'
        ]);
        $I->seeResponseCodeIs(422);
        $I->seeResponseContainsJson([
            'field' => 'login',
            'message' => 'Please enter a valid mobile number or email address.'
        ]);
    }

    public function wrongPhone(ApiTester $I)
    {
        $I->sendPOST('/signup', [
            'login' => '998974457018a',
            'password' => 'fdfdsfsdfs'
        ]);
        $I->seeResponseCodeIs(422);
        $I->seeResponseContainsJson([
            'field' => 'login',
            'message' => 'Please enter a valid mobile number or email address.'
        ]);
    }

    public function shortPhoneNumber(ApiTester $I)
    {
        $I->sendPOST('/signup', [
            'login' => '9989744',
            'password' => 'fdfdsfsdfs'
        ]);
        $I->seeResponseCodeIs(422);
        $I->seeResponseContainsJson([
            'field' => 'login',
            'message' => 'Please enter a valid mobile number or email address.'
        ]);
    }

    public function longPhoneNumber(ApiTester $I)
    {
        $I->sendPOST('/signup', [
            'login' => '9989744570188888',
            'password' => 'fdfdsfsdfs'
        ]);
        $I->seeResponseCodeIs(422);
        $I->seeResponseContainsJson([
            'field' => 'login',
            'message' => 'Please enter a valid mobile number or email address.'
        ]);
    }

    public function successEmail(ApiTester $I)
    {
        $I->sendPOST('/signup', [
            'login' => 'signup@test.com',
            'password' => 'password_1'
        ]);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseJsonMatchesJsonPath('$.status');
        $I->seeResponseJsonMatchesJsonPath('$.createdAt');

    }

    public function successPhone(ApiTester $I)
    {
        $I->sendPOST('/signup', [
            'login' => 998974457020,
            'password' => 'fdfdsfsdfs'
        ]);

        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseJsonMatchesJsonPath('$.status');
        $I->seeResponseJsonMatchesJsonPath('$.createdAt');
    }

    public function duplicateEmail(ApiTester $I)
    {

        $I->sendPOST('/signup', [
            'login' => 'signup@test.com',
            'password' => 'password_0'
        ]);
        $I->seeResponseCodeIs(422);
        $I->seeResponseContainsJson([
            [
                'field' => 'login',
                'message' => 'This email address already exists, but not activated'
            ],
            [
                'field' => 'Status',
                'message' => 20
            ],
        ]);
    }


    public function duplicatePhone(ApiTester $I)
    {
        $I->sendPOST('/signup', [
            'login' => 998974457020,
            'password' => 'password_0'
        ]);
        $I->seeResponseCodeIs(422);
        $I->seeResponseContainsJson([
            [
                'field' => 'login',
                'message' => 'This phone number already exists, but not activated'
            ],
            [
                'field' => 'Status',
                'message' => 20
            ],
        ]);
    }
}
