<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace box\services\review;


use box\entities\review\Review;
use box\repositories\review\ReviewRepository;
use box\repositories\UserRepository;
use box\forms\reviews\ReviewForm;

/**
 * @property ReviewRepository $reviews
 */

class ReviewService
{
    public $reviews;

    public function __construct(
        ReviewRepository $reviewRepository
    )
    {
        $this->reviews = $reviewRepository;
    }

    /**
     * @param ReviewForm $form
     * @throws \RuntimeException
     * @throws \box\repositories\NotFoundException
     */
    public function create(ReviewForm $form)
    {
        $parent = $this->reviews->find($form->parentId);
        $review = Review::create($form->title, $form->text, $form->type, $form->item_id, $form->score);
        $review->appendTo($parent);
        $this->reviews->save($review);
    }
}