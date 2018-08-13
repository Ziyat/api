<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace box\readModels;


use box\entities\carousel\Carousel;
use yii\data\ActiveDataProvider;

class CarouselReadModel
{
    public function getCarousels(): ActiveDataProvider
    {
        return new ActiveDataProvider([
            'query' => Carousel::find()
        ]);
    }

    public function getCarousel($id): ?Carousel
    {
        return Carousel::findOne($id);

    }
}