<?php

namespace box\services;

use box\entities\Meta;
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

    public function create(CarouselForm $form)
    {
        $carousel = Carousel::create(
                    $form->title,
                    $form->description,
                    $form->text,
                    $form->type,
                    $form->item_id);
        $this->carousels->save($carousel);
        return $carousel;
    }

//    public function edit($id, CarouselForm $form): void
//    {
//        $carousel = $this->carousels->get($id);
//        $carousel->edit(
//            $form->name,
//            $form->slug ?: Inflector::slug($form->name),
//            $form->photo,
//            new Meta(
//                $form->meta->title,
//                $form->meta->description,
//                $form->meta->keywords
//            )
//        );
//        $this->carousels->save($carousel);
//    }

//    public function remove($id)
//    {
//        $carousel = $this->carousels->get($id);
//        if ($this->products->existsByCarousel($carousel->id)) {
//            throw new \DomainException('Unable to remove carousel with products.');
//        }
//        $this->carousels->remove($carousel);
//    }
}