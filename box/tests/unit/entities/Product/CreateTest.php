<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace box\tests\unit\entities\Product;

use box\entities\Meta;
use box\entities\shop\product\Product;
use Codeception\Test\Unit;

class ProductCreateTest extends Unit
{
    public function testSuccess()
    {
        $product = Product::create(
            $brandId = 1,
            $categoryId = 1,
            $name = 'Iphone 6',
            $description = 'Iphone 6',
            $meta = new Meta($name,$description,'apple')
        );

        $product->setQuantity($quantity = 22);

        $this->assertEquals($brandId, 1);
        $this->assertEquals($categoryId, 1);
        $this->assertEquals($name, $product->name);
        $this->assertEquals($description, $product->description);
        $this->assertEquals($meta->title, $product->meta->title);
        $this->assertEquals($meta->description, $product->meta->description);
        $this->assertEquals($meta->keywords, $product->meta->keywords);
        $this->assertEquals($quantity, $product->quantity);
    }

    public function testChangeStatus()
    {
        $product = Product::create(
            $brandId = 1,
            $categoryId = 1,
            $name = 'Iphone 6',
            $description = 'Iphone 6',
            $meta = new Meta($name,$description,'apple')
        );

        $this->assertEquals($product->status, Product::STATUS_DRAFT);

        $product->activate();

        $this->assertEquals($product->status, Product::STATUS_ACTIVE);

        $product->draft();

        $this->assertEquals($product->status, Product::STATUS_DRAFT);
    }

    public function testSetPriceType()
    {
        $product = Product::create(
            $brandId = 1,
            $categoryId = 1,
            $name = 'Iphone 6',
            $description = 'Iphone 6',
            $meta = new Meta($name,$description,'apple')
        );

        $this->assertNull($product->price_type);

        $product->setPriceType(Product::PRICE_TYPE_AUCTION);

        $this->assertEquals($product->price_type, Product::PRICE_TYPE_AUCTION);

        $product->setPriceType(Product::PRICE_TYPE_BARGAIN);

        $this->assertEquals($product->price_type, Product::PRICE_TYPE_BARGAIN);

        $product->setPriceType(Product::PRICE_TYPE_FIX);

        $this->assertEquals($product->price_type, Product::PRICE_TYPE_FIX);
    }
}