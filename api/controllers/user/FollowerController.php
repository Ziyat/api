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
     *     path="/user/follow",
     *     tags={"Followers"},
     *     description="Returns boolean true",
     *     @SWG\Parameter(name="follow_id", in="formData", required=true, type="string"),
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
     *     path="/user/unfollow",
     *     tags={"Followers"},
     *     description="Returns boolean true",
     *     @SWG\Parameter(name="follow_id", in="formData", required=true, type="string"),
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
        } catch (\Exception $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        return true;
    }

    /**
     * @SWG\Get(
     *     path="/user/following",
     *     tags={"Followers"},
     *     description="Returns data array",
     *     @SWG\Response(
     *         response=200,
     *         description="Success response",
     *         @SWG\Schema(ref="#/definitions/ArrayProfile")
     *     ),
     *     security={{"Bearer": {}}}
     * )
     * @return ArrayDataProvider
     */

    public function actionFollowing()
    {
        return $this->users->getFollowing(Yii::$app->user->id);
    }

    /**
     * @SWG\Get(
     *     path="/user/followers",
     *     tags={"Followers"},
     *     description="Returns data array",
     *     @SWG\Response(
     *         response=200,
     *         description="Success response",
     *         @SWG\Schema(ref="#/definitions/ArrayProfile")
     *     ),
     *     security={{"Bearer": {}}}
     * )
     * @return ArrayDataProvider
     */

    public function actionFollowers()
    {
        return $this->users->getFollowers(Yii::$app->user->id);
    }
}
/**
 * @SWG\Definition(
 *     definition="ArrayProfile",
 *     type="array",
 *     @SWG\Items(ref="#/definitions/Profile")
 *)
 */

