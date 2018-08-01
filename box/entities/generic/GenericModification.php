<?php

namespace box\entities\generic;

use box\entities\shop\Characteristic;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property string $value
 * @property integer $characteristic_id
 * @property integer $main_photo_id
 * @property integer $generic_product_id
 * @property Characteristic $characteristic
 * @property GenericPhoto $mainPhoto
 */
class GenericModification extends ActiveRecord
{
    public static function create($characteristic_id, $value, $main_photo_id): self
    {
        $modification = new static();
        $modification->value = $value;
        $modification->main_photo_id = $main_photo_id;
        $modification->characteristic_id = $characteristic_id;
        return $modification;
    }

    public function edit($characteristic_id, $value, $main_photo_id): void
    {
        $this->value = $value;
        $this->characteristic_id = $characteristic_id;
        $this->main_photo_id = $main_photo_id;
    }

    public function change($value, $main_photo_id): void
    {
        $this->value = $value;
        $this->main_photo_id = $main_photo_id;
    }

    public function isForCharacteristic($id): bool
    {
        return $this->characteristic_id == $id;
    }

    public function getCharacteristic(): ActiveQuery
    {
        return $this->hasOne(Characteristic::class, ['id' => 'characteristic_id']);
    }

    public function getMainPhoto(): ActiveQuery
    {
        return $this->hasOne(GenericPhoto::class, ['id' => 'main_photo_id']);
    }

    public static function tableName(): string
    {
        return '{{%generic_modifications}}';
    }
}