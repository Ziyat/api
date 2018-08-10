<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace api\controllers;


use box\forms\carousel\CarouselForm;
use box\services\CarouselService;
use yii\web\ForbiddenHttpException;

class CarouselController extends BearerCrudController
{
    public $service;

    public function __construct(
        string $id,
        $module,
        CarouselService $service,
        array $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->service = $service;
    }


    /**
     * @throws ForbiddenHttpException
     */
    public function actionCreate()
    {
        if (!\Yii::$app->user->can('create')) {
            throw new ForbiddenHttpException('Forbidden');
        }

        $form = new CarouselForm();
        $form->load(\Yii::$app->request->bodyParams,'');
        $form->validate();
        return $form;
    }
}