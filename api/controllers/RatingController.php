<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace api\controllers;


use box\forms\rating\RatingForm;
use box\readModels\RatingReadModel;
use box\services\rating\RatingManageService;
use yii\web\BadRequestHttpException;

/**
 * @property RatingManageService $manageService
 * @property RatingReadModel $ratings
 */
class RatingController extends BearerController
{
    private $manageService;
    private $ratings;

    public function __construct(
        string $id,
        $module,
        RatingManageService $service,
        RatingReadModel $readModel,
        array $config = []
    )
    {
        $this->manageService = $service;
        $this->ratings = $readModel;
        parent::__construct($id, $module, $config);
    }

    /**
     * @SWG\Post(
     *     path="/ratings",
     *     tags={"Ratings"},
     *     @SWG\Parameter(name="type", in="formData", required=true, type="integer"),
     *     @SWG\Parameter(name="item_id", in="formData", required=true, type="integer"),
     *     @SWG\Parameter(name="score", in="formData", required=false, type="number"),
     *     @SWG\Parameter(name="name", in="formData", required=true, type="string"),
     *     description="Create rating",
     *     @SWG\Response(
     *         response=200,
     *         description="Success response",
     *     ),
     *     security={{"Bearer": {}}}
     * )
     * @return \box\entities\rating\Rating|RatingForm
     * @throws BadRequestHttpException
     * @throws \yii\base\InvalidArgumentException
     */
    public function actionCreate()
    {
        $form = new RatingForm();
        $form->load(\Yii::$app->request->bodyParams, '');
        if ($form->validate()) {
            try {
                $rating = $this->manageService->create($form);
                return $rating;
            } catch (\Exception $e) {
                throw new BadRequestHttpException($e->getMessage());
            }
        }

        return $form;
    }

    /**
     * @SWG\Post(
     *     path="/ratings/{id}",
     *     tags={"Ratings"},
     *     @SWG\Parameter(name="id", in="path", required=true, type="integer"),
     *     @SWG\Parameter(name="type", in="formData", required=true, type="integer"),
     *     @SWG\Parameter(name="item_id", in="formData", required=true, type="integer"),
     *     @SWG\Parameter(name="score", in="formData", required=false, type="number"),
     *     @SWG\Parameter(name="name", in="formData", required=true, type="string"),
     *     description="Create rating",
     *     @SWG\Response(
     *         response=200,
     *         description="Success response",
     *     ),
     *     security={{"Bearer": {}}}
     * )
     *
     * @param $id
     * @return \box\entities\rating\Rating|RatingForm
     * @throws BadRequestHttpException
     * @throws \box\repositories\NotFoundException
     * @throws \yii\base\InvalidArgumentException
     */
    public function actionEdit($id)
    {
        $rating = $this->ratings->find($id);
        $form = new RatingForm($rating);
        $form->load(\Yii::$app->request->bodyParams, '');
        if ($form->validate()) {
            try {
                $rating = $this->manageService->edit($rating->id, $form);
                return $rating;
            } catch (\Exception $e) {
                throw new BadRequestHttpException($e->getMessage());
            }
        }

        return $form;
    }

}