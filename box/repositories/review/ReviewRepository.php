<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace box\repositories\review;

use box\entities\review\Review;
use box\repositories\NotFoundException;

class ReviewRepository
{
    /**
     * @param $id
     * @return Review
     * @throws NotFoundException
     */
    public function find($id): Review
    {
        if (!$review = Review::findOne($id)) {
            throw new NotFoundException('Review is not found.');
        }
        return $review;
    }

    /**
     * @param Review $review
     * @throws \RuntimeException
     */
    public function save(Review $review)
    {
        if (!$review->save()) {
            throw new \RuntimeException('Saving error.');
        }
    }

    /**
     * @param Review $review
     * @throws \RuntimeException
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function remove(Review $review)
    {
        if (!$review->delete()) {
            throw new \RuntimeException('Removing error.');
        }
    }
}