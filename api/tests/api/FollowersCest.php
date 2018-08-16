<?php

namespace api\tests\functional;

use api\tests\ApiTester;
use box\entities\user\Follower;
use common\fixtures\ProfileFixture;
use common\fixtures\TokenFixture;
use common\fixtures\UserFixture;

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
            Follower::NOT_APPROVE => [
                'id' => 1
            ],
            Follower::APPROVE => []
        ]);

    }

    public function successNotApproveFollowers(ApiTester $I)
    {
        $I->amBearerAuthenticated('token-correct');
        $I->sendGET('/user/followers');
        $I->seeResponseCodeIs(200);
        $I->seeResponseContainsJson([
            Follower::NOT_APPROVE => [
                'id' => 3
            ],
            Follower::APPROVE => []
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
            Follower::NOT_APPROVE => [],
            Follower::APPROVE => [
                'id' => 3
            ]
        ]);
    }

    public function successApproveFollowing(ApiTester $I)
    {
        $I->amBearerAuthenticated('token-correct-id-3');
        $I->sendGET('/user/following');
        $I->seeResponseCodeIs(200);
        $I->seeResponseContainsJson([
            Follower::NOT_APPROVE => [],
            Follower::APPROVE => [
                'id' => 1
            ]
        ]);
    }

    public function successDisapprove(ApiTester $I)
    {
        $I->amBearerAuthenticated('token-correct');
        $I->sendPATCH('/user/followers/disapprove/3');
        $I->seeResponseCodeIs(200);
        $I->seeResponseContainsJson([
            0 => true,
        ]);

        $I->amBearerAuthenticated('token-correct');
        $I->sendGET('/user/followers');
        $I->seeResponseCodeIs(200);
        $I->seeResponseContainsJson([
            Follower::NOT_APPROVE => [
                'id' => 3
            ],
            Follower::APPROVE => []
        ]);

        $I->amBearerAuthenticated('token-correct-id-3');
        $I->sendGET('/user/following');
        $I->seeResponseCodeIs(200);
        $I->seeResponseContainsJson([
            Follower::NOT_APPROVE => [
                'id' => 1
            ],
            Follower::APPROVE => []
        ]);
    }

    public function successFollowing(ApiTester $I)
    {
        $I->amBearerAuthenticated('token-correct-id-3');
        $I->sendPATCH('/user/following/1');
        $I->seeResponseCodeIs(200);
        $I->seeResponseContainsJson([
            Follower::NOT_APPROVE => [
                'id' => 1
            ],
            Follower::APPROVE => null
        ]);
    }

    public function successFollower(ApiTester $I)
    {
        $I->amBearerAuthenticated('token-correct');
        $I->sendPATCH('/user/followers/1');
        $I->seeResponseCodeIs(200);
        $I->seeResponseContainsJson([
            Follower::NOT_APPROVE => [
                'id' => 3
            ],
            Follower::APPROVE => null
        ]);
    }

    public function publicFollowers(ApiTester $I)
    {
        $I->sendGET('/public/user/1/followers');
        $I->seeResponseCodeIs(200);
        $I->seeResponseContainsJson([
            Follower::NOT_APPROVE => [
                'id' => 3
            ],
            Follower::APPROVE => []
        ]);
    }

    public function publicFollowing(ApiTester $I)
    {
        $I->sendGET('/public/user/3/following');
        $I->seeResponseCodeIs(200);
        $I->seeResponseContainsJson([
            Follower::NOT_APPROVE => [
                'id' => 1
            ],
            Follower::APPROVE => []
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

        $I->sendGET('/public/user/3/following');
        $I->seeResponseCodeIs(200);
        $I->seeResponseContainsJson([
            Follower::NOT_APPROVE => [],
            Follower::APPROVE => []
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
