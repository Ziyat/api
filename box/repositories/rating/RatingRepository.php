<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace box\repositories\rating;

use box\entities\rating\Rating;
use box\repositories\NotFoundException;

class RatingRepository
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

    /**
     * @param Rating $rating
     * @throws \RuntimeException
     */
    public function save(Rating $rating)
    {
        if (!$rating->save()) {
            throw new \RuntimeException('Saving error.');
        }
    }

    /**
     * @param Rating $rating
     * @throws \RuntimeException
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function remove(Rating $rating)
    {
        if (!$rating->delete()) {
            throw new \RuntimeException('Removing error.');
        }
    }
}