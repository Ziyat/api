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

    public function findByTypeAndItemId($type, $item_id)
    {
        return new ActiveDataProvider([
            'query' => Review::find()->where(['type' => $type, 'item_id' => $item_id])->orderBy(['score' => SORT_DESC])
        ]);
    }
}