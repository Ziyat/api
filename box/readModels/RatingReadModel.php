<?php

namespace box\readModels;

use box\entities\rating\Rating;
use box\repositories\NotFoundException;
use yii\data\ActiveDataProvider;

class RatingReadModel
{
    /**
     * @param $id
     * @return Rating
     * @throws NotFoundException
     */
    public function find($id): Rating
    {
        if (!$rating = Rating::findOne($id)) {
            throw new NotFoundException('Rating is not found.');
        }
        return $rating;
    }
}