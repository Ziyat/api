<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace api\controllers\user;

use api\controllers\BearerController;
use box\entities\user\User;
use box\helpers\UserHelper;
use box\readModels\UserReadRepository;
use box\services\UserService;
use box\forms\user\UserEditForm;
use Yii;
use yii\data\ArrayDataProvider;
use yii\web\BadRequestHttpException;

class FollowerController extends BearerController
{

    protected $service;
    protected $users;

    public function __construct(
        $id,
        $module,
        UserService $service,
        UserReadRepository $users,
        $config = []
    )
    {
        parent::__construct($id, $module, $config);
        $this->service = $service;
        $this->users = $users;
    }

    /**
     * @SWG\Patch(
     *     path="/user/follow/{follow_id}",
     *     tags={"Followers"},
     *     description="Returns boolean true",
     *     @SWG\Parameter(name="follow_id", in="path", required=true, type="string"),
     *     @SWG\Response(
     *         response=200,
     *         description="Success response boolean"
     *     ),
     *     security={{"Bearer": {}}}
     * )
     * @params $follow_id
     * @return bool
     * @throws BadRequestHttpException
     */

    public function actionFollow($follow_id)
    {
        try {
            $this->service->setFollow($follow_id, Yii::$app->user->id);
            return true;
        } catch (\Exception $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
    }

    /**
     * @SWG\Patch(
     *     path="/user/unfollow/{follow_id}",
     *     tags={"Followers"},
     *     description="Returns boolean true",
     *     @SWG\Parameter(name="follow_id", in="path", required=true, type="integer"),
     *     @SWG\Response(
     *         response=200,
     *         description="Success response boolean"
     *     ),
     *     security={{"Bearer": {}}}
     * )
     * @param $follow_id
     * @return bool
     * @throws BadRequestHttpException
     */

    public function actionUnFollow($follow_id)
    {
        try {
            $this->service->unFollow($follow_id, Yii::$app->user->id);
            return true;
        } catch (\Exception $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
    }

    /**
     * @SWG\Get(
     *     path="/user/following",
     *     tags={"Followers"},
     *     description="Returns data array",
     *     @SWG\Response(
     *         response=200,
     *         description="Success response",
     *         @SWG\Items(
     *              @SWG\Property(property="KEY [0] -> {approve}", type="array", @SWG\Items(ref="#/definitions/Profile")),
     *              @SWG\Property(property="KEY [1] -> {not approve}", type="array", @SWG\Items(ref="#/definitions/Profile"))
     *         ),
     *     ),
     *     security={{"Bearer": {}}}
     * )
     * @SWG\Patch(
     *     path="/user/following/{following_id}",
     *     tags={"Followers"},
     *     description="Returns data array",
     *     @SWG\Parameter(name="following_id", in="path", required=true, type="integer"),
     *     @SWG\Response(
     *         response=200,
     *         description="Success response",
     *         @SWG\Schema(ref="#/definitions/Profile")
     *     ),
     *     security={{"Bearer": {}}}
     * )
     * @return ArrayDataProvider|array
     * @throws BadRequestHttpException
     */

    public function actionFollowing($following_id = null)
    {
        if ($following_id) {
            try {
                $result = $this->users->getFollowing(Yii::$app->user->id, $following_id);
            } catch (\Exception $e) {
                throw new BadRequestHttpException($e->getMessage());
            }
        } else {
            $result = $this->users->getFollowing(Yii::$app->user->id);
        }

        return $result;
    }

    /**
     * @SWG\Get(
     *     path="/user/followers",
     *     tags={"Followers"},
     *     description="Returns data array",
     *     @SWG\Response(
     *         response=200,
     *         description="Success response",
     *         @SWG\Items(
     *              @SWG\Property(property="KEY [0] -> {approve}", type="array", @SWG\Items(ref="#/definitions/Profile")),
     *              @SWG\Property(property="KEY [1] -> {not approve}", type="array", @SWG\Items(ref="#/definitions/Profile")),
     *          ),
     *     ),
     *     security={{"Bearer": {}}}
     * )
     * @SWG\Patch(
     *     path="/user/followers/{follower_id}",
     *     tags={"Followers"},
     *     description="Returns data array",
     *     @SWG\Parameter(name="follower_id", in="path", required=true, type="integer"),
     *     @SWG\Response(
     *         response=200,
     *         description="Success response",
     *         @SWG\Property(ref="#/definitions/Profile")
     *     ),
     *     security={{"Bearer": {}}}
     * )
     * @return ArrayDataProvider|array
     * @throws BadRequestHttpException
     */

    public function actionFollowers($follower_id = null)
    {
        if ($follower_id) {
            try {
                $result = $this->users->getFollowers(Yii::$app->user->id, $follower_id);
            } catch (\Exception $e) {
                throw new BadRequestHttpException($e->getMessage());
            }
        } else {
            $result = $this->users->getFollowers(Yii::$app->user->id);
        }

        return $result;
    }


    /**
     * @SWG\Patch(
     *     path="/user/followers/approve/{follower_id}",
     *     tags={"Followers"},
     *     description="Returns boolean",
     *     @SWG\Parameter(name="follower_id", in="path", required=false, type="string"),
     *     @SWG\Response(
     *         response=200,
     *         description="Success response boolean",
     *     ),
     *     security={{"Bearer": {}}}
     * )
     * @param $follower_id
     * @return bool
     * @throws BadRequestHttpException
     */

    public function actionApprove($follower_id)
    {
        try {
            $this->service->approve($follower_id, Yii::$app->user->id);
            return true;
        } catch (\Exception $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
    }

    /**
     * @SWG\Patch(
     *     path="/user/followers/disapprove/{follower_id}",
     *     tags={"Followers"},
     *     description="Return boolean",
     *     @SWG\Parameter(name="follower_id", in="path", required=false, type="string"),
     *     @SWG\Response(
     *         response=200,
     *         description="Success response boolean",
     *     ),
     *     security={{"Bearer": {}}}
     * )
     * @param $follower_id
     * @return bool
     * @throws BadRequestHttpException
     */

    public function actionDisapprove($follower_id)
    {
        try {
            $this->service->approve($follower_id, Yii::$app->user->id);
            return true;
        } catch (\Exception $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
    }
}

