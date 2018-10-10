<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace box\entities\generic;


use yii\db\ActiveRecord;

/**
 * @property integer $id
 * @property integer $generic_product_id
 * @property string $name
 */
class GenericRating extends ActiveRecord
{
    public static function create($name)
    {
        $rating = new static();
        $rating->name = $name;
        return $rating;
    }

    public function edit($name)
    {
        $this->name = $name;
    }

    public function isIdEqualTo($id)
    {
        return $this->id == $id;
    }

    public static function tableName(): string
    {
        return '{{%generic_ratings}}';
    }
}