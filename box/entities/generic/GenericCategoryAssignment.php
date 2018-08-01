<?php

namespace box\entities\generic;

use yii\db\ActiveRecord;

/**
 * @property integer $generic_product_id;
 * @property integer $category_id;
 */
class GenericCategoryAssignment extends ActiveRecord
{
    public static function create($categoryId): self
    {
        $assignment = new static();
        $assignment->category_id = $categoryId;
        return $assignment;
    }

    public function isForCategory($id): bool
    {
        return $this->category_id == $id;
    }

    public static function tableName(): string
    {
        return '{{%generic_category_assignments}}';
    }
}