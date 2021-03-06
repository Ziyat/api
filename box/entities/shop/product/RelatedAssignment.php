<?php

namespace box\entities\shop\product;

use yii\db\ActiveRecord;

/**
 * @property integer $product_id;
 * @property integer $related_id;
 */
class RelatedAssignment extends ActiveRecord
{
    public static function create($productId): self
    {
        $assignment = new static();
        $assignment->related_id = $productId;
        return $assignment;
    }

    public function isForProduct($id): bool
    {
        return $this->related_id == $id;
    }

    public static function tableName(): string
    {
        return '{{%related_assignments}}';
    }
}