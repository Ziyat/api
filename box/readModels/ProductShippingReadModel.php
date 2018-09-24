<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace box\readModels;

use box\entities\shop\product\Shipping;
use box\repositories\NotFoundException;
use yii\data\ActiveDataProvider;

class ProductShippingReadModel
{
    /**
     * @param $product_id
     * @param $id
     * @return Shipping
     * @throws NotFoundException
     */
    public function get($product_id, $id): Shipping
    {
        if (!$shipping = Shipping::findOne(['id' => $id,'product_id' => $product_id])) {
            throw new NotFoundException('Product Shipping is not found.');
        }
        return $shipping;
    }

    public function getAll($product_id): ActiveDataProvider
    {
        return new ActiveDataProvider([
            'query' => Shipping::find()->andWhere(['product_id' => $product_id])
        ]);
    }
}