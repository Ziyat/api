<?php

namespace box\entities\shop\product\events;

use box\entities\shop\product\Product;

class ProductAppearedInStock
{
    public $product;

    public function __construct(Product $product)
    {
        $this->product = $product;
    }
}