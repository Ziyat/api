<?php

namespace box\readModels;

use box\entities\review\Review;
use box\repositories\NotFoundException;
use yii\data\ActiveDataProvider;

class ReviewReadModel
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

    public function getReviews(): ActiveDataProvider
    {
        return new ActiveDataProvider([
            'query' => Review::find()
        ]);
    }
}