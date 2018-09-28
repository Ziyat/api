<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace api\controllers;


use box\services\review\ReviewService;
use box\forms\reviews\ReviewForm;
use yii\web\BadRequestHttpException;

/**
 * @property ReviewService $manageService
 */

class ReviewController extends BearerController
{
    private $manageService;

    public function __construct(
        string $id,
        $module,
        ReviewService $service,
        array $config = []
    )
    {
        $this->manageService = $service;
        parent::__construct($id, $module, $config);
    }

    /**
     * @return ReviewForm
     * @throws BadRequestHttpException
     * @throws \RuntimeException
     * @throws \box\repositories\NotFoundException
     * @throws \yii\base\InvalidArgumentException
     */
    public function actionCreate()
    {
        $form = new ReviewForm();
        $form->load(\Yii::$app->request->bodyParams, '');
        if ($form->validate()) {
            try {
                $this->manageService->create($form);
            } catch (\DomainException $e) {
                throw new BadRequestHttpException($e->getMessage());
            }
        }
        return $form;
    }

}