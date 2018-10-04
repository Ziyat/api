<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace box\services\rating;


use box\entities\generic\GenericProduct;
use box\entities\rating\Rating;
use box\entities\shop\product\Product;
use box\entities\user\User;
use box\forms\rating\RatingForm;
use box\repositories\generic\ProductRepository as GenericProductRepository;
use box\repositories\NotFoundException;
use box\repositories\ProductRepository;
use box\repositories\rating\RatingRepository;
use box\repositories\UserRepository;

/**
 * @property RatingRepository $ratings
 * @property ProductRepository $userProducts
 * @property GenericProductRepository $genericProducts
 * @property UserRepository $users
 */
class RatingManageService
{
    public $ratings;
    public $userProducts;
    public $genericProducts;
    public $users;

    public function __construct(
        RatingRepository $ratingRepository,
        ProductRepository $userProductRepository,
        GenericProductRepository $genericProductRepository,
        UserRepository $userRepository
    )
    {
        $this->ratings = $ratingRepository;
        $this->userProducts = $userProductRepository;
        $this->genericProducts = $genericProductRepository;
        $this->users = $userRepository;
    }

    /**
     * @param RatingForm $form
     * @return Rating
     * @throws \RuntimeException
     * @throws \box\repositories\NotFoundException
     */
    public function create(RatingForm $form): Rating
    {
        $item = $this->getItem($form->type, $form->item_id);

        $rating = Rating::create($form->type, $item->id, $form->score);

        $this->ratings->save($rating);

        return $rating;
    }

    /**
     * @param $id
     * @param RatingForm $form
     * @return Rating
     * @throws NotFoundException
     * @throws \RuntimeException
     */
    public function edit($id, RatingForm $form): Rating
    {
        $rating = $this->ratings->find($id);

        $item = $this->getItem($form->type, $form->item_id);

        $rating->edit($form->type, $item->id, $form->score);

        $this->ratings->save($rating);

        return $rating;
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
        $rating = $this->ratings->find($id);
        $this->ratings->remove($rating);
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
            case Rating::TYPE_USER_PRODUCT:
                return $this->userProducts->get($item_id);
            case Rating::TYPE_GENERIC_PRODUCT:
                return $this->genericProducts->get($item_id);
            case Rating::TYPE_USER:
                return $this->users->find($item_id);
            default:
                throw new NotFoundException('Item not found!');
        }
    }
}