<?php

namespace box\repositories;

use box\entities\shop\product\Product;
use box\repositories\NotFoundException;

class ProductRepository
{
    public function get($id): Product
    {
        if (!$product = Product::findOne($id)) {
            throw new NotFoundException('Product is not found.');
        }
        return $product;
    }

    public function save(Product $product)
    {
        if (!$product->save()) {
            throw new \RuntimeException('Saving error.');
        }
    }

    public function remove(Product $product)
    {
        if (!$product->delete()) {
            throw new \RuntimeException('Removing error.');
        }
    }
}