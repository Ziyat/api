<?php

namespace box\services;

use box\entities\Meta;
use box\entities\Shop\Brand;
use box\forms\shop\BrandForm;
use box\repositories\BrandRepository;
use box\repositories\ProductRepository;
use yii\helpers\Inflector;

class BrandService
{
    private $brands;
    private $products;

    public function __construct(BrandRepository $brands , ProductRepository $products)
    {
        $this->brands = $brands;
        $this->products = $products;
    }

    public function create(BrandForm $form)
    {
        $brand = Brand::create(
            $form->name,
            $form->slug ?: Inflector::slug($form->name),
            $form->photo,
            new Meta(
                $form->meta->title,
                $form->meta->description,
                $form->meta->keywords
            )
        );
        $this->brands->save($brand);
        return $brand;
    }

    public function edit($id, BrandForm $form): void
    {
        $brand = $this->brands->get($id);
        $brand->edit(
            $form->name,
            $form->slug ?: Inflector::slug($form->name),
            $form->photo,
            new Meta(
                $form->meta->title,
                $form->meta->description,
                $form->meta->keywords
            )
        );
        $this->brands->save($brand);
    }

    public function remove($id)
    {
        $brand = $this->brands->get($id);
        if ($this->products->existsByBrand($brand->id)) {
            throw new \DomainException('Unable to remove brand with products.');
        }
        $this->brands->remove($brand);
    }
}