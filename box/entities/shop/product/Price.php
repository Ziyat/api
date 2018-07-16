<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace box\entities\shop\product;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * @property integer $id
 * @property integer $product_id
 * @property integer $created_at
 * @property float $cur_price
 * @property float $end_price
 * @property float $max_price
 * @property integer $deadline
 */
class Price extends ActiveRecord
{
    public const DEAL_OPENED = 1;
    public const DEAL_CLOSED = 0;

    public static function create($curPrice): self
    {
        $price = new static();
        $price->cur_price = $curPrice;
        $price->created_at = time();
        return $price;
    }

    public function setDeadline($deadline)
    {
        $this->deadline = $deadline;
    }

    public function setEndPrice($endPrice)
    {
        $this->end_price = $endPrice;
    }

    public function setMaxPrice($maxPrice)
    {
        $this->max_price = $maxPrice;
    }

    public function getProduct(): ActiveQuery
    {
        return $this->hasOne(Product::class, ['id' => 'product_id']);
    }

    public static function tableName()
    {
        return '{{%prices}}';
    }
}