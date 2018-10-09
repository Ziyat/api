<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace box\services\review;


use box\entities\generic\GenericProduct;
use box\entities\review\Review;
use box\entities\shop\product\Product;
use box\entities\user\User;
use box\forms\reviews\PhotosForm;
use box\forms\reviews\ReviewForm;
use box\repositories\generic\ProductRepository as GenericProductRepository;
use box\repositories\NotFoundException;
use box\repositories\ProductRepository;
use box\repositories\review\ReviewRepository;
use box\repositories\UserRepository;

/**
 * @property ReviewRepository $reviews
 * @property ProductRepository $userProducts
 * @property GenericProductRepository $genericProducts
 * @property UserRepository $users
 */
class ReviewManageService
{
    public $reviews;
    public $userProducts;
    public $genericProducts;
    public $users;

    public function __construct(
        ReviewRepository $reviewRepository,
        ProductRepository $userProductRepository,
        GenericProductRepository $genericProductRepository,
        UserRepository $userRepository
    )
    {
        $this->reviews = $reviewRepository;
        $this->userProducts = $userProductRepository;
        $this->genericProducts = $genericProductRepository;
        $this->users = $userRepository;
    }

    /**
     * @param ReviewForm $form
     * @return Review
     * @throws \RuntimeException
     * @throws \box\repositories\NotFoundException
     */
    public function create(ReviewForm $form): Review
    {
        $item = $this->getItem($form->type, $form->item_id);

        $parent = $this->reviews->find($form->parentId);
        $review = Review::create($form->title, $form->text, $form->type, $item->id, $form->score);
        $review->appendTo($parent);

        if (is_array($form->photos->files)) {
            foreach ($form->photos->files as $file) {
                $review->addPhoto($file);
            }
        }

        $this->reviews->save($review);
        return $review;
    }

    /**
     * @param $id
     * @param ReviewForm $form
     * @return Review
     * @throws \DomainException
     * @throws \RuntimeException
     * @throws \box\repositories\NotFoundException
     */
    public function edit($id, ReviewForm $form): Review
    {
        $review = $this->reviews->find($id);

        $this->assertIsNotRoot($review);

        $review->edit($form->title, $form->text, $form->type, $form->item_id, $form->score);

        if ($form->parentId != $review->parent->id) {
            $parent = $this->reviews->find($form->parentId);
            $review->appendTo($parent);
        }

        $this->reviews->save($review);

        return $review;
    }

    /**
     * @param $id
     * @throws NotFoundException
     * @throws \DomainException
     * @throws \RuntimeException
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function remove($id)
    {
        $review = $this->reviews->find($id);
        $this->assertIsNotRoot($review);
        $this->reviews->remove($review);
    }

    /**
     * @param $id
     * @param PhotosForm $form
     * @return Review
     * @throws NotFoundException
     * @throws \RuntimeException
     */
    public function addPhotos($id, PhotosForm $form): Review
    {
        $review = $this->reviews->find($id);
        foreach ($form->files as $file) {
            $review->addPhoto($file);
        }
        $this->reviews->save($review);
        return $review;
    }

    /**
     * @param $id
     * @param $photoId
     * @throws NotFoundException
     * @throws \DomainException
     * @throws \RuntimeException
     */
    public function movePhotoUp($id, $photoId): void
    {
        $review = $this->reviews->find($id);
        $review->movePhotoUp($photoId);
        $this->reviews->save($review);
    }

    /**
     * @param $id
     * @param $photoId
     * @throws NotFoundException
     * @throws \DomainException
     * @throws \RuntimeException
     */
    public function movePhotoDown($id, $photoId): void
    {
        $review = $this->reviews->find($id);
        $review->movePhotoDown($photoId);
        $this->reviews->save($review);
    }

    /**
     * @param $id
     * @param $photoId
     * @throws NotFoundException
     * @throws \DomainException
     * @throws \RuntimeException
     */
    public function removePhoto($id, $photoId): void
    {
        $review = $this->reviews->find($id);
        $review->removePhoto($photoId);
        $this->reviews->save($review);
    }

    /**
     * @param Review $review
     * @throws \DomainException
     */
    private function assertIsNotRoot(Review $review): void
    {
        if ($review->isRoot()) {
            throw new \DomainException('Unable to manage the root review.');
        }
    }

    /**
     * @param $type
     * @param $item_id
     * @return GenericProduct|Product|User
     * @throws NotFoundException
     */
    private function getItem($type, $item_id)
    {
        switch ($type) {
            case Review::TYPE_USER_PRODUCT:
                return $this->userProducts->get($item_id);
            case Review::TYPE_GENERIC_PRODUCT:
                return $this->genericProducts->get($item_id);
            case Review::TYPE_USER:
                return $this->users->find($item_id);
            default:
                throw new NotFoundException('Item not found!');
        }
    }
}