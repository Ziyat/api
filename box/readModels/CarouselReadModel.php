<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace box\readModels;


use box\entities\carousel\Carousel;
use box\repositories\NotFoundException;
use yii\data\ActiveDataProvider;

class CarouselReadModel
{
    public function getCarousels(): ActiveDataProvider
    {
        return new ActiveDataProvider([
            'query' => Carousel::find()
        ]);
    }

    public function getCarouselsActive(): ActiveDataProvider
    {
        return new ActiveDataProvider([
            'query' => Carousel::find()->andWhere(['status' => Carousel::STATUS_ACTIVE])
        ]);
    }

    public function getCarousel($id): ?Carousel
    {
        return Carousel::findOne($id);

    }


    /**
     * @param $carousel_id
     * @param $item_id
     * @return \box\entities\carousel\Item
     * @throws NotFoundException
     */
    public function getItemById($carousel_id, $item_id)
    {
        $carousel = $this->getCarousel($carousel_id);
        foreach ($carousel->items as $item) {
            if ($item->isIdEqualTo($item_id)) {
                return $item;
            }
        }

        throw new NotFoundException('Item not found');
    }

    public function getItemsByCarouselId($carousel_id)
    {
        $carousel = $this->getCarousel($carousel_id);
        return new ActiveDataProvider([
            'query' => $carousel->items
        ]);

        throw new NotFoundException('Item not found');
    }
}