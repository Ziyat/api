<?php

namespace box\repositories;

use box\entities\Shop\Brand;
use box\repositories\NotFoundException;

class BrandRepository
{
    public function get($id): Brand
    {
        if (!$brand = Brand::findOne($id)) {
            throw new NotFoundException('Brand is not found.');
        }
        return $brand;
    }

    public function save(Brand $brand)
    {
        if (!$brand->save()) {
            throw new \RuntimeException('Saving error.');
        }
    }

    public function remove(Brand $brand)
    {
        if (!$brand->delete() && $brand->cleanFiles()) {
            throw new \RuntimeException('Removing error.');
        }
    }
}