<?php

namespace box\repositories;

use box\entities\shop\product\Product;
use box\repositories\NotFoundException;

class ProductRepository
{
    public function get($id): Product
    {
        if (!$brand = Product::findOne($id)) {
            throw new NotFoundException('Product is not found.');
        }
        return $brand;
    }

    public function save(Product $brand)
    {
        if (!$brand->save()) {
            throw new \RuntimeException('Saving error.');
        }
    }

    public function remove(Product $brand)
    {
        if (!$brand->delete()) {
            throw new \RuntimeException('Removing error.');
        }
    }
}