<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace box\readModels;


use box\entities\user\Address;
use box\entities\user\Follower;
use box\entities\user\User;
use box\repositories\NotFoundException;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;

class UserReadRepository
{
    /**
     * @param $id
     * @param null $following_id
     * @return ArrayDataProvider|array
     */
    public function getFollowing($id, $following_id = null)
    {
        /**
         * @var User $user
         */
        $user = User::findOne($id);

        if ($following_id) {
            $result = [Follower::APPROVE => null, Follower::NOT_APPROVE => null];
            foreach ($user->notApproveFollowingAssignments as $notApproveFollowing) {
                /**
                 * @var Follower $notApproveFollowing
                 */
                if ($notApproveFollowing->user_id == $following_id) {
                    $result[Follower::NOT_APPROVE] = $notApproveFollowing->following;
                }
            }

            foreach ($user->approveFollowingAssignments as $approveFollowing) {
                /**
                 * @var Follower $approveFollowing
                 */
                if ($approveFollowing->user_id == $following_id) {
                    $result[Follower::APPROVE] = $approveFollowing->following;
                }
            }
        } else {
            $result = [
                Follower::NOT_APPROVE => $user->notApproveFollowing,
                Follower::APPROVE => $user->approveFollowing
            ];
        }


        return new ArrayDataProvider([
            'allModels' => $result,
            'sort' => [
                'attributes' => ['created_at'],
            ],
        ]);
    }

    public function getFollowers($id, $follower_id = null)
    {
        /**
         * @var User $user
         */
        $user = User::findOne($id);

        if ($follower_id) {
            $result = [Follower::APPROVE => null, Follower::NOT_APPROVE => null];
            foreach ($user->notApproveFollowersAssignments as $notApproveFollower) {
                /**
                 * @var Follower $notApproveFollower
                 */
                if ($notApproveFollower->user_id == $follower_id) {
                    $result[Follower::NOT_APPROVE] = $notApproveFollower->follower;
                }
            }

            foreach ($user->approveFollowersAssignments as $approveFollower) {
                /**
                 * @var Follower $approveFollower
                 */
                if ($approveFollower->user_id == $follower_id) {
                    $result[Follower::APPROVE] = $approveFollower->follower;
                }
            }
        } else {

            $result = [
                Follower::NOT_APPROVE => $user->notApproveFollowers,
                Follower::APPROVE => $user->approveFollowers
            ];
        }

        return new ArrayDataProvider([
            'allModels' => $result,
            'sort' => [
                'attributes' => ['created_at'],
            ],
        ]);
    }

    /**
     * @return ActiveDataProvider
     */
    public function getUsers(): ActiveDataProvider
    {
        return new ActiveDataProvider([
            'query' => User::find()->roleUser()->active()
        ]);
    }

    /**
     * @param $id
     * @return User|null
     * @throws NotFoundException
     */
    public function getUser($id): ?User
    {
        if (!$user = User::find()->andWhere(['id' => $id])->roleUser()->active()->one()) {
            throw new NotFoundException('User not found.');
        }

        return $user;
    }

    /**
     * @param $userId
     * @param $address_id
     * @return Address
     * @throws NotFoundException
     */
    public function getUserAddress($userId, $address_id): Address
    {
        if (!$user = User::findOne($userId)) {
            throw new NotFoundException('User not found.');
        }

        /**
         * @var Address $address
         */

        if(!$address = $user->getAddresses()->where(['id' =>$address_id])->one()){
            throw new NotFoundException('User address not found.');
        }

        return $address;
    }
}