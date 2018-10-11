<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace api\controllers;

use box\readModels\NotificationReadModel;

/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 * Class NotificationController
 * @package api\controllers
 * @property NotificationReadModel $notifications
 */

class NotificationController extends BearerController
{
    public $notifications;

    public function __construct(
        string $id,
        $module,
        NotificationReadModel $readModel,
        array $config = []
    )
    {
        $this->notifications = $readModel;
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
}