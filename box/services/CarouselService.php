<?php

namespace box\services;

use box\entities\carousel\Carousel;
use box\forms\carousel\CarouselForm;
use box\repositories\CarouselRepository;

class CarouselService
{
    private $carousels;

    public function __construct(CarouselRepository $carousels)
    {
        $this->carousels = $carousels;
    }

    public function create(CarouselForm $form): Carousel
    {
        $carousel = Carousel::create(
            $form->title,
            $form->subTitle,
            $form->description,
            $form->text,
            $form->type,
            $form->item_id
        );

        if (is_array($form->images->files)) {
            foreach ($form->images->files as $file) {
                $carousel->addImage($file);
            }
        }
        $this->carousels->save($carousel);
        return $carousel;
    }

    /**
     * @param $id
     * @param CarouselForm $form
     * @return Carousel
     * @throws \box\repositories\NotFoundException
     */
    public function edit($id, CarouselForm $form)
    {
        $carousel = $this->carousels->get($id);
        $carousel->edit(
            $form->title,
            $form->subTitle,
            $form->description,
            $form->text,
            $form->type,
            $form->item_id
        );
        $this->carousels->save($carousel);
        return $carousel;
    }

    /**
     * @param $id
     * @throws \Throwable
     * @throws \box\repositories\NotFoundException
     * @throws \yii\db\StaleObjectException
     */
    public function remove($id)
    {
        $carousel = $this->carousels->get($id);
        $this->carousels->remove($carousel);
    }
}