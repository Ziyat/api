<?php

namespace box\entities\shop\product;

use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property string $value
 * @property string $price
 * @property integer $characteristic_id
 * @property integer $main_photo_id
 * @property integer $product_id
 * @property int $quantity
 */
class Modification extends ActiveRecord
{
    public static function create($value, $price, $main_photo_id, $characteristic_id, $quantity): self
    {
        $modification = new static();
        $modification->value = $value;
        $modification->price = $price;
        $modification->main_photo_id = $main_photo_id;
        $modification->characteristic_id = $characteristic_id;
        $modification->quantity = $quantity;
        return $modification;
    }

    public function edit($value, $price, $main_photo_id, $characteristic_id, $quantity): void
    {
        $this->value = $value;
        $this->price = $price;
        $this->characteristic_id = $characteristic_id;
        $this->quantity = $quantity;
        $this->main_photo_id = $main_photo_id;
    }

    public function checkout($quantity): void
    {
        if ($quantity > $this->quantity) {
            throw new \DomainException('Only ' . $this->quantity . ' items are available.');
        }
        $this->quantity -= $quantity;
    }

    public static function tableName(): string
    {
        return '{{%modifications}}';
    }
}