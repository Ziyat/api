<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace api\controllers;

use box\forms\user\PushTokenForm;
use box\readModels\NotificationReadModel;
use box\services\UserService;
use yii\web\BadRequestHttpException;

/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 * Class NotificationController
 * @package api\controllers
 * @property NotificationReadModel $notifications
 * @property UserService $userService
 */
class NotificationController extends BearerController
{
    public $notifications;
    public $userService;

    public function __construct(
        string $id,
        $module,
        NotificationReadModel $readModel,
        UserService $userService,
        array $config = []
    )
    {
        $this->notifications = $readModel;
        $this->userService = $userService;
        parent::__construct($id, $module, $config);
    }

    /**
     * @throws \box\repositories\NotFoundException
     */
    public function actionNew()
    {
        return $this->notifications->findNew(\Yii::$app->user->id);
    }

    /**
     * @throws \box\repositories\NotFoundException
     */
    public function actionAll()
    {
        return $this->notifications->findAll(\Yii::$app->user->id);
    }

    /**
     * @SWG\Post(
     *     path="/notification/push-token",
     *     tags={"Profile"},
     *     @SWG\Response(
     *         response=200,
     *         description="Success response boolean",
     *     ),
     *     security={{"Bearer": {}}}
     * )
     *
     * @return \box\entities\user\User|PushTokenForm
     * @throws BadRequestHttpException
     * @throws \yii\base\InvalidArgumentException
     */
    public function actionPushToken()
    {
        $form = new PushTokenForm();
        $form->load(\Yii::$app->request->bodyParams, '');
        if ($form->validate()) {
            try {
                $user = $this->userService->addPushToken(\Yii::$app->user->id, $form);
                return $user;
            } catch (\Exception $e) {
                throw new BadRequestHttpException($e->getMessage());
            }
        }
        return $form;
    }
}