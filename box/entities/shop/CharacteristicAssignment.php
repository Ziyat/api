<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace box\entities\shop;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\Json;
use yii\helpers\VarDumper;

/**
 * Class CharacteristicAssignment
 * @package box\entities\shop
 * @property integer $category_id
 * @property integer $characteristic_id
 * @property array $variants
 * @property Characteristic[] $characteristics
 */
class CharacteristicAssignment extends ActiveRecord
{
    public $variants;

    public static function create($id, $variants): self
    {
        $assignment = new static();
        $assignment->category_id = $id;
        $assignment->variants = $variants;
        return $assignment;
    }

    public function isForCategory($id): bool
    {
        return $this->category_id == $id;
    }

    public function afterFind(): void
    {
        $this->variants = array_filter(Json::decode($this->getAttribute('variants_json')));
        parent::afterFind();
    }

    public function beforeSave($insert): bool
    {
        $this->setAttribute('variants_json', Json::encode(array_filter($this->variants)));
        return parent::beforeSave($insert);
    }



    public static function tableName(): string
    {
        return '{{%characteristic_assignments}}';
    }
}