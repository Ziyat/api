<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace api\controllers;


use box\forms\reviews\PhotosForm;
use box\forms\reviews\ReviewForm;
use box\readModels\ReviewReadModel;
use box\services\review\ReviewManageService;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;

/**
 * @property ReviewManageService $manageService
 * @property ReviewReadModel $reviews
 */
class ReviewController extends BearerController
{
    private $manageService;
    private $reviews;

    public function __construct(
        string $id,
        $module,
        ReviewManageService $service,
        ReviewReadModel $readModel,
        array $config = []
    )
    {
        $this->manageService = $service;
        $this->reviews = $readModel;
        parent::__construct($id, $module, $config);
    }

    /**
     * @SWG\Get(
     *     path="/reviews/{type}/{item_id}",
     *     tags={"Reviews"},
     *     @SWG\Parameter(name="type", in="path", required=true, type="integer"),
     *     @SWG\Parameter(name="item_id", in="path", required=true, type="integer"),
     *     description="Return reviews array by type and item_id",
     *     @SWG\Response(
     *         response=200,
     *         description="Success response",
     *     ),
     *     security={{"Bearer": {}}}
     * )
     * @param $type
     * @param $item_id
     * @return \yii\data\ActiveDataProvider
     */
    public function actionAllByItem($type, $item_id)
    {
        return $this->reviews->findByTypeAndItemId($type, $item_id);
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
     *     @SWG\Parameter(name="files", in="formData", required=false, type="file"),
     *     description="Create new review, Return review object",
     *     @SWG\Response(
     *         response=200,
     *         description="Success response",
     *     ),
     *     security={{"Bearer": {}}}
     * )
     * @return \box\entities\review\Review|ReviewForm
     * @throws BadRequestHttpException
     * @throws \Exception
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
            } catch (\Exception $e) {
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
     *     security={{"Bearer": {}}}
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
        $review = $this->reviews->find($id);
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
     * @SWG\Delete(
     *     path="/reviews/{id}",
     *     tags={"Reviews"},
     *     @SWG\Parameter(name="id", in="path", required=true, type="integer"),
     *     description="Delete review by id",
     *     @SWG\Response(
     *         response=200,
     *         description="Success response",
     *     ),
     *     security={{"Bearer": {}}}
     * )
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

    /**
     * @SWG\GET(
     *     path="/reviews/{id}/children",
     *     tags={"Reviews"},
     *     @SWG\Parameter(name="id", in="path", required=true, type="integer"),
     *     description="Delete review by id",
     *     @SWG\Response(
     *         response=200,
     *         description="Success response",
     *     ),
     *     security={{"Bearer": {}}}
     * )
     *
     * @param $id
     * @return \box\entities\review\Review[]
     * @throws BadRequestHttpException
     */

    public function actionChildren($id)
    {
        try {
            $review = $this->reviews->find($id);
            return $review->children;
        } catch (\Exception $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
    }

    /**
     * @SWG\GET(
     *     path="/reviews/{id}/parent",
     *     tags={"Reviews"},
     *     @SWG\Parameter(name="id", in="path", required=true, type="integer"),
     *     description="Delete review by id",
     *     @SWG\Response(
     *         response=200,
     *         description="Success response",
     *     ),
     *     security={{"Bearer": {}}}
     * )
     *
     * @param $id
     * @return \box\entities\review\Review
     * @throws BadRequestHttpException
     */

    public function actionParent($id)
    {
        try {
            $review = $this->reviews->find($id);
            return $review->parent;
        } catch (\Exception $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
    }

    /**
     * @SWG\GET(
     *     path="/reviews/{id}/parents",
     *     tags={"Reviews"},
     *     @SWG\Parameter(name="id", in="path", required=true, type="integer"),
     *     description="Delete review by id",
     *     @SWG\Response(
     *         response=200,
     *         description="Success response",
     *     ),
     *     security={{"Bearer": {}}}
     * )
     *
     * @param $id
     * @return \box\entities\review\Review[]
     * @throws BadRequestHttpException
     */

    public function actionParents($id)
    {
        try {
            $review = $this->reviews->find($id);
            return $review->getParents()->andWhere(['!=','depth', 0])->all();
        } catch (\Exception $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
    }


    /**
     * @SWG\Post(
     *     path="/reviews/{id}/photos",
     *     tags={"Reviews"},
     *     description="added photos",
     *     @SWG\Parameter(name="id", in="path", required=true, type="integer"),
     *     @SWG\Parameter(name="files", in="formData", required=true, type="file"),
     *     @SWG\Response(
     *         response=200,
     *         description="Success response",
     *     ),
     *     security={{"Bearer": {}}}
     * )
     *
     * @param $id
     * @return \box\entities\review\Review
     * @throws NotFoundHttpException
     * @throws \RuntimeException
     * @throws \yii\base\InvalidArgumentException
     */

    public function actionAddPhotos($id)
    {
        $form = new PhotosForm();
        $form->load(\Yii::$app->request->bodyParams, '');
        $form->validate();
        try {
            $product = $this->manageService->addPhotos($id, $form);
            $response = \Yii::$app->getResponse();
            $response->setStatusCode(202);
            return $product;
        } catch (NotFoundHttpException $e) {
            throw new NotFoundHttpException($e->getMessage());
        }
    }

    /**
     * @SWG\Delete(
     *     path="/reviews/{id}/photos/{photo_id}",
     *     tags={"Reviews"},
     *     description="delete photo",
     *     @SWG\Parameter(name="id", in="path", required=true, type="integer"),
     *     @SWG\Parameter(name="photo_id", in="path", required=true, type="integer"),
     *     @SWG\Response(
     *         response=200,
     *         description="Success response"
     *     ),
     *     security={{"Bearer": {}}}
     * )
     *
     * @param $id
     * @param $photo_id
     * @return bool
     * @throws \DomainException
     * @throws \RuntimeException
     * @throws \box\repositories\NotFoundException
     */

    public function actionDeletePhoto($id, $photo_id)
    {
        try {
            $this->manageService->removePhoto($id, $photo_id);
        } catch (\DomainException $e) {
            throw new \DomainException($e->getMessage());
        }
        return true;
    }

    /**
     * @SWG\Patch(
     *     path="/reviews/{id}/photos/{photo_id}/up",
     *     tags={"Reviews"},
     *     description="move up photo",
     *     @SWG\Parameter(name="id", in="path", required=true, type="integer"),
     *     @SWG\Parameter(name="photo_id", in="path", required=true, type="integer"),
     *     @SWG\Response(
     *         response=200,
     *         description="Success response"
     *     ),
     *     security={{"Bearer": {}}}
     * )
     *
     * @param $id
     * @param $photo_id
     * @return bool
     * @throws \DomainException
     * @throws \RuntimeException
     * @throws \box\repositories\NotFoundException
     */
    public function actionMovePhotoUp($id, $photo_id)
    {
        $this->manageService->movePhotoUp($id, $photo_id);
        return true;
    }

    /**
     * @SWG\Patch(
     *     path="/reviews/{id}/photos/{photo_id}/down",
     *     tags={"Reviews"},
     *     description="move down photo",
     *     @SWG\Parameter(name="id", in="path", required=true, type="integer"),
     *     @SWG\Parameter(name="photo_id", in="path", required=true, type="integer"),
     *     @SWG\Response(
     *         response=200,
     *         description="Success response"
     *     ),
     *     security={{"Bearer": {}}}
     * )
     *
     * @param $id
     * @param $photo_id
     * @return bool
     * @throws \DomainException
     * @throws \RuntimeException
     * @throws \box\repositories\NotFoundException
     */

    public function actionMovePhotoDown($id, $photo_id)
    {
        $this->manageService->movePhotoDown($id, $photo_id);
        return true;
    }

}