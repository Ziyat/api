<?php

namespace box\services;

use box\entities\carousel\Carousel;
use box\forms\carousel\CarouselForm;
use box\forms\carousel\ItemForm;
use box\repositories\CarouselRepository;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;

class CarouselService
{
    private $carousels;

    public function __construct(CarouselRepository $carousels)
    {
        $this->carousels = $carousels;
    }

    public function create(CarouselForm $form): Carousel
    {
        $carousel = Carousel::create($form->title, $form->subTitle, $form->type, $form->template_id);
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
        $carousel->edit($form->title, $form->subTitle, $form->type, $form->template_id);
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

    /**
     * @param $carousel_id
     * @param ItemForm $form
     * @return Carousel
     * @throws \box\repositories\NotFoundException
     */
    public function addItem($carousel_id, ItemForm $form)
    {
        $carousel = $this->carousels->get($carousel_id);
        $carousel->addItem(
            $form->title,
            $form->description,
            $form->text,
            $form->item_id,
            $form->images
        );
        $this->carousels->save($carousel);
        return $carousel;
    }

    /**
     * @param $carousel_id
     * @param $item_id
     * @param ItemForm $form
     * @return Carousel
     * @throws \box\repositories\NotFoundException
     */
    public function editItem($carousel_id, $item_id, ItemForm $form)
    {
        $carousel = $this->carousels->get($carousel_id);
        $carousel->editItem(
            $item_id,
            $form->title,
            $form->description,
            $form->text,
            $form->item_id
        );
        $this->carousels->save($carousel);
        return $carousel;
    }

    /**
     * @param $carousel_id
     * @param $item_id
     * @return Carousel
     * @throws \box\repositories\NotFoundException
     */
    public function removeItem($carousel_id, $item_id)
    {
        $carousel = $this->carousels->get($carousel_id);
        $carousel->removeItem($item_id);
        $this->carousels->save($carousel);
        return $carousel;
    }

    /**
     * @param $carousel_id
     * @param $item_id
     * @param ItemForm $form
     * @return Carousel
     * @throws \box\repositories\NotFoundException
     */
    public function addItemImages($carousel_id, $item_id, ItemForm $form)
    {
        $carousel = $this->carousels->get($carousel_id);

        foreach ($form->images->files as $file) {
            $carousel->addItemImage($item_id, $file);
        }

        $this->carousels->save($carousel);
        return $carousel;
    }

    /**
     * @param $carousel_id
     * @param $item_id
     * @param $image_id
     * @throws \box\repositories\NotFoundException
     */
    public function removeItemImages($carousel_id, $item_id, $image_id)
    {
        $carousel = $this->carousels->get($carousel_id);

        $carousel->removeItemImage($item_id, $image_id);

        $this->carousels->save($carousel);
    }
}