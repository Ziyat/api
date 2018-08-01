<?php

namespace box\repositories\generic;

use box\entities\generic\GenericProduct;
use box\repositories\NotFoundException;

class ProductRepository
{
    /**
     * @param $id
     * @return GenericProduct
     * @throws \box\repositories\NotFoundException
     */
    public function get($id): GenericProduct
    {
        if (!$product = GenericProduct::findOne($id)) {
            throw new NotFoundException('GenericProduct is not found.');
        }
        return $product;
    }

    public function existsByBrand($id): bool
    {
        return GenericProduct::find()->andWhere(['brand_id' => $id])->exists();
    }

    public function save(GenericProduct $product)
    {
        if (!$product->save()) {
            throw new \RuntimeException('Saving error.');
        }
    }

    /**
     * @param GenericProduct $product
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function remove(GenericProduct $product)
    {
        if (!$product->delete()) {
            throw new \RuntimeException('Removing error.');
        }
    }
}