<?php

namespace box\repositories;

use box\entities\carousel\Carousel;

class CarouselRepository
{
    /**
     * @param $id
     * @return Carousel
     * @throws NotFoundException
     */
    public function get($id): Carousel
    {
        if (!$carousel = Carousel::findOne($id)) {
            throw new NotFoundException('Carousel is not found.');
        }
        return $carousel;
    }

    public function save(Carousel $carousel)
    {
        if (!$carousel->save()) {
            throw new \RuntimeException('Saving error.');
        }
    }

    /**
     * @param Carousel $carousel
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function remove(Carousel $carousel)
    {
        if (!$carousel->delete() && $carousel->cleanFiles()) {
            throw new \RuntimeException('Removing error.');
        }
    }
}