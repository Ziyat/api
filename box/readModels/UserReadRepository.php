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

    public function getFollowing($id): ArrayDataProvider
    {
        /**
         * @var User $user
         */
        $user = User::findOne($id);
        return new ArrayDataProvider([
            'allModels' => $user->following,
            'sort' => [
                'attributes' => ['created_at'],
            ],
        ]);
    }

    public function getFollowers($id): ArrayDataProvider
    {
        /**
         * @var User $user
         */
        $user = User::findOne($id);
        return new ArrayDataProvider([
            'allModels' => $user->followers,
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
}