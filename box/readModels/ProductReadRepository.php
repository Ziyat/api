<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace box\readModels;



use box\entities\shop\product\Product;
use box\entities\user\User;
use box\repositories\NotFoundException;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;

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
}