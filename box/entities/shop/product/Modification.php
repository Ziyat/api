<?php

namespace box\entities\shop\product;

use box\entities\shop\Characteristic;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property string $value
 * @property string $price
 * @property integer $characteristic_id
 * @property integer $main_photo_id
 * @property integer $product_id
 * @property int $quantity
 * @property Characteristic $characteristic
 * @property Photo $mainPhoto
 */
class Modification extends ActiveRecord
{
    public static function create($characteristic_id, $value, $price, $quantity, $main_photo_id): self
    {
        $modification = new static();
        $modification->value = $value;
        $modification->price = $price;
        $modification->main_photo_id = $main_photo_id;
        $modification->characteristic_id = $characteristic_id;
        $modification->quantity = $quantity;
        return $modification;
    }

    public function edit($characteristic_id, $value, $price, $quantity, $main_photo_id): void
    {
        $this->value = $value;
        $this->price = $price;
        $this->characteristic_id = $characteristic_id;
        $this->quantity = $quantity;
        $this->main_photo_id = $main_photo_id;
    }

    public function change($value, $price, $main_photo_id, $quantity): void
    {
        $this->value = $value;
        $this->price = $price;
        $this->main_photo_id = $main_photo_id;
        $this->quantity = $quantity;
    }

    public function isForCharacteristic($id,$modificationId = null): bool
    {
        return $modificationId
            ? $this->characteristic_id == $id && $this->id == $modificationId
            : $this->characteristic_id == $id;
    }

    public function changeMainPhoto($mainPhotoId)
    {
        $this->main_photo_id = $mainPhotoId;
    }

    public function getCharacteristic(): ActiveQuery
    {
        return $this->hasOne(Characteristic::class, ['id' => 'characteristic_id']);
    }

    public function getMainPhoto(): ActiveQuery
    {
        return $this->hasOne(Photo::class, ['id' => 'main_photo_id']);
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