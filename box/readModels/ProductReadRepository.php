<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace box\readModels;



use box\entities\shop\product\Product;
use box\repositories\NotFoundException;
use yii\data\ActiveDataProvider;

class ProductReadRepository
{
    /**
     * @return ActiveDataProvider
     */
    public function getUserProducts($id): ActiveDataProvider
    {
        return new ActiveDataProvider([
            'query' => Product::find()->andWhere(['created_by' => $id])->active()
        ]);
    }

    /**
     * @param $id
     * @return Product|array
     * @throws NotFoundException
     */
    public function getProductsById($id): Product
    {
        if (!$product = Product::find()->andWhere(['id' => $id])->active()->one()) {
            throw new NotFoundException('Product not found!');
        }
        return $product;
    }
}