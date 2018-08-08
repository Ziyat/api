<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace box\readModels;


use box\entities\shop\product\Product;
use box\entities\user\Follower;
use box\entities\user\queries\UserQuery;
use box\entities\user\User;
use box\repositories\NotFoundException;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\web\NotFoundHttpException;

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
            $result = new ArrayDataProvider([
                'allModels' => [$user->notApproveFollowing, $user->approveFollowing],
                'sort' => [
                    'attributes' => ['created_at'],
                ],
            ]);
        }


        return $result;
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
            $result = new ArrayDataProvider([
                'allModels' => [$user->notApproveFollowers, $user->approveFollowers],
                'sort' => [
                    'attributes' => ['created_at'],
                ],
            ]);
        }

        return $result;
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
}