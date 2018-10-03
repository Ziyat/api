<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace box\readModels;



use box\entities\shop\product\Product;
use box\entities\shop\product\Shipping;
use box\entities\user\User;
use box\forms\publicForms\UserProductShippingForm;
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

    /**
     * @param $product_id
     * @param UserProductShippingForm $form
     * @return array
     * @throws NotFoundException
     * @throws \LogicException
     * @throws \yii\base\InvalidArgumentException
     */
    public function getShipping($product_id, UserProductShippingForm $form)
    {
        $userCountryId = null;
        $product = $this->getProductsById($product_id);
        if ($user = User::findOne($form->user_id)) {
            $userAddress = $user->getAddresses()->where(['default' => 1])->one();
            $userCountryId = $userAddress->country_id;
        }

        $shippingResult = [
            'destinations' => [
                'free' => [],
                'pickup' => [],
                'other' => [],
            ],
            'free' => [],
            'pickup' => [],
            'other' => [],
        ];

        foreach ($product->shipping as $shipping) {
            /**
             * @var Shipping $shipping
             */
            if ($userCountryId && $shipping->isInDestination($userCountryId)) {
                if($shipping->isFree()){
                    $shippingResult['destinations']['free'][] = $shipping;
                }elseif($shipping->isPickup()){
                    $shippingResult['destinations']['pickup'][] = $shipping;
                }else{
                    $shippingResult['destinations']['other'][] = $shipping;
                }
            } elseif ($form->free && $shipping->isFree()) {
                $shippingResult['free'][] = $shipping;
            } elseif ($form->pickup && $shipping->isPickup()) {
                $shippingResult['pickup'][] = $shipping;
            }else{
                $shippingResult['other'][] = $shipping;
            }
        }
        return $shippingResult;
    }

}