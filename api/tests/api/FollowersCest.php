<?php

namespace api\tests\functional;

use api\tests\ApiTester;
use common\fixtures\ProfileFixture;
use common\fixtures\TokenFixture;
use common\fixtures\UserFixture;
use yii\helpers\VarDumper;

/**
 * Class FollowersCest
 */
class FollowersCest
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

    public function emptyFollowers(ApiTester $I)
    {
        $I->amBearerAuthenticated('token-correct-id-3');
        $I->sendGET('/user/followers');
        $I->seeResponseCodeIs(200);
        $I->seeResponseContainsJson([
            0 => [],
            1 => [],
        ]);
    }

    public function emptyFollowing(ApiTester $I)
    {
        $I->amBearerAuthenticated('token-correct-id-3');
        $I->sendGET('/user/following');
        $I->seeResponseCodeIs(200);
        $I->seeResponseContainsJson([
            0 => [],
            1 => [],
        ]);
    }

    public function successFollow(ApiTester $I)
    {
        $I->amBearerAuthenticated('token-correct-id-3');
        $I->sendPATCH('/user/follow/1');
        $I->seeResponseCodeIs(200);
        $I->seeResponseContainsJson([
            0 => true,
        ]);
    }

    public function errorFollow(ApiTester $I)
    {
        $I->amBearerAuthenticated('token-correct-id-3');
        $I->sendPATCH('/user/follow/33');
        $I->seeResponseCodeIs(400);
        $I->seeResponseContainsJson([
            'message' => 'User not found!',
        ]);
    }

    public function successNotApproveFollowing(ApiTester $I)
    {
        $I->amBearerAuthenticated('token-correct-id-3');
        $I->sendGET('/user/following');
        $I->seeResponseCodeIs(200);
        $I->seeResponseContainsJson([
            0 => [],
            1 => [
                [
                    'id' => 1
                ]
            ],
        ]);
    }

    public function successNotApproveFollowers(ApiTester $I)
    {
        $I->amBearerAuthenticated('token-correct');
        $I->sendGET('/user/followers');
        $I->seeResponseCodeIs(200);
        $I->seeResponseContainsJson([
            0 => [],
            1 => [
                [
                    'id' => 3
                ]
            ],
        ]);
    }

    public function successApprove(ApiTester $I)
    {
        $I->amBearerAuthenticated('token-correct');
        $I->sendPATCH('/user/followers/approve/3');
        $I->seeResponseCodeIs(200);
        $I->seeResponseContainsJson([
            0 => true,
        ]);
    }

    public function successApproveFollowers(ApiTester $I)
    {
        $I->amBearerAuthenticated('token-correct');
        $I->sendGET('/user/followers');
        $I->seeResponseCodeIs(200);
        $I->seeResponseContainsJson([
            0 => [
                [
                    'id' => 3
                ]
            ],
            1 => [],
        ]);
    }

    public function successApproveFollowing(ApiTester $I)
    {
        $I->amBearerAuthenticated('token-correct-id-3');
        $I->sendGET('/user/following');
        $I->seeResponseCodeIs(200);
        $I->seeResponseContainsJson([
            0 => [
                [
                    'id' => 1
                ]
            ],
            1 => [],
        ]);
    }

    public function successDisapprove(ApiTester $I)
    {
        $I->amBearerAuthenticated('token-correct');
        $I->sendPATCH('/user/followers/approve/3');
        $I->seeResponseCodeIs(200);
        $I->seeResponseContainsJson([
            0 => true,
        ]);

        $I->amBearerAuthenticated('token-correct');
        $I->sendGET('/user/followers');
        $I->seeResponseCodeIs(200);
        $I->seeResponseContainsJson([
            0 => [],
            1 => [
                [
                    'id' => 3
                ]
            ],
        ]);

        $I->amBearerAuthenticated('token-correct-id-3');
        $I->sendGET('/user/following');
        $I->seeResponseCodeIs(200);
        $I->seeResponseContainsJson([
            0 => [],
            1 => [
                [
                    'id' => 1
                ]
            ],
        ]);
    }

    public function successFollowing(ApiTester $I)
    {
        $I->amBearerAuthenticated('token-correct-id-3');
        $I->sendPATCH('/user/following/1');
        $I->seeResponseCodeIs(200);
        $I->seeResponseContainsJson([
            0 => [
                'id' => 1
            ],
            1 => null,
        ]);
    }

    public function successFollower(ApiTester $I)
    {
        $I->amBearerAuthenticated('token-correct');
        $I->sendPATCH('/user/followers/1');
        $I->seeResponseCodeIs(200);
        $I->seeResponseContainsJson([
            0 => [
                'id' => 3
            ],
            1 => null,
        ]);
    }


    public function successUnFollow(ApiTester $I)
    {
        $I->amBearerAuthenticated('token-correct-id-3');
        $I->sendPATCH('/user/unfollow/1');
        $I->seeResponseCodeIs(200);
        $I->seeResponseContainsJson([
            0 => true
        ]);
    }

    public function errorUnFollow(ApiTester $I)
    {
        $I->amBearerAuthenticated('token-correct-id-3');
        $I->sendPATCH('/user/follow/33');
        $I->seeResponseCodeIs(400);
        $I->seeResponseContainsJson([
            'message' => 'User not found!',
        ]);
    }
}
