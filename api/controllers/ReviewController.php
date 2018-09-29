<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace api\controllers;


use box\forms\reviews\ReviewForm;
use box\readModels\ReviewReadModel;
use box\services\review\ReviewManageService;
use yii\web\BadRequestHttpException;

/**
 * @property ReviewManageService $manageService
 * @property ReviewReadModel $readModel
 */

class ReviewController extends BearerController
{
    private $manageService;
    private $readModel;

    public function __construct(
        string $id,
        $module,
        ReviewManageService $service,
        ReviewReadModel $readModel,
        array $config = []
    )
    {
        $this->manageService = $service;
        $this->readModel = $readModel;
        parent::__construct($id, $module, $config);
    }

    /**
     * @SWG\Post(
     *     path="/reviews",
     *     tags={"Reviews"},
     *     @SWG\Parameter(name="title", in="formData", required=false, type="string"),
     *     @SWG\Parameter(name="parentId", in="formData", required=false, type="integer"),
     *     @SWG\Parameter(name="text", in="formData", required=true, type="string"),
     *     @SWG\Parameter(name="type", in="formData", required=true, type="integer"),
     *     @SWG\Parameter(name="item_id", in="formData", required=true, type="integer"),
     *     @SWG\Parameter(name="score", in="formData", required=false, type="integer"),
     *     description="Create new review, Return review object",
     *     @SWG\Response(
     *         response=200,
     *         description="Success response",
     *     ),
     * )
     * @return \box\entities\review\Review|ReviewForm
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
                $review = $this->manageService->create($form);
                return $review;
            } catch (\DomainException $e) {
                throw new BadRequestHttpException($e->getMessage());
            }
        }
        return $form;
    }

    /**
     * @SWG\Post(
     *     path="/reviews/{id}",
     *     tags={"Reviews"},
     *     @SWG\Parameter(name="id", in="path", required=true, type="integer"),
     *     @SWG\Parameter(name="title", in="formData", required=false, type="string"),
     *     @SWG\Parameter(name="parentId", in="formData", required=false, type="integer"),
     *     @SWG\Parameter(name="text", in="formData", required=false, type="string"),
     *     @SWG\Parameter(name="type", in="formData", required=false, type="integer"),
     *     @SWG\Parameter(name="item_id", in="formData", required=false, type="integer"),
     *     @SWG\Parameter(name="score", in="formData", required=false, type="integer"),
     *     description="Edit review by id, Return review object",
     *     @SWG\Response(
     *         response=200,
     *         description="Success response",
     *     ),
     * )
     * @param $id
     * @return \box\entities\review\Review|ReviewForm
     * @throws BadRequestHttpException
     * @throws \RuntimeException
     * @throws \box\repositories\NotFoundException
     * @throws \yii\base\InvalidArgumentException
     */
    public function actionUpdate($id)
    {
        $review = $this->readModel->find($id);
        $form = new ReviewForm($review);
        $form->load(\Yii::$app->request->bodyParams, '');
        if ($form->validate()) {
            try {
                $review = $this->manageService->edit($review->id, $form);
                return $review;
            } catch (\DomainException $e) {
                throw new BadRequestHttpException($e->getMessage());
            }
        }
        return $form;
    }

    /**
     * @param $id
     * @throws BadRequestHttpException
     * @throws \Throwable
     */
    public function actionDelete($id)
    {
        try {
            $this->manageService->remove($id);
        } catch (\Exception $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
    }

}