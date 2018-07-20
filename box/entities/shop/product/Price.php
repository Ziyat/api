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
 * @property float $current
 * @property float $end
 * @property float $max
 * @property float $buy_now
 * @property integer $deadline
 */
class Price extends ActiveRecord
{
    public static function create($current): self
    {
        $price = new static();
        $price->current = $current;
        $price->created_at = time();
        return $price;
    }

    public function setDeadline($deadline)
    {
        $this->deadline = $deadline;
    }

    public function setEndPrice($end)
    {
        $this->end = $end;
    }

    public function setMaxPrice($max)
    {
        $this->max = $max;
    }

    public function setBuyNow($buyNow)
    {
        $this->buy_now = $buyNow;
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