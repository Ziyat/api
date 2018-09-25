<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace box\readModels;

use box\entities\shop\product\Shipping;
use box\entities\shop\shipping\ShippingServiceRates;
use box\entities\user\User;
use box\forms\shop\shipping\SearchRatesForm;
use box\repositories\NotFoundException;
use PHPUnit\Framework\MockObject\Builder\Identity;
use yii\data\ActiveDataProvider;
use yii\helpers\VarDumper;

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

    /**
     * @param $user
     * @param SearchRatesForm $form
     * @return array|\yii\db\ActiveRecord[]
     * @throws NotFoundException

     */
    public function getRates($user, SearchRatesForm $form)
    {
        /**
         * @var User $user
         */
        if(!$user->addresses){
            throw new NotFoundException('User Address not found!');
        }

        $address = $user->getAddresses()->where(['default' => 1])->one();

        return ShippingServiceRates::find()->andWhere(['country_id' => $address->country->id])->all();
    }
}