<?php

namespace box\entities\generic;

use yii\db\ActiveRecord;

/**
 * @property integer $generic_product_id;
 * @property integer $tag_id;
 */
class GenericTagAssignment extends ActiveRecord
{
    public static function create($tagId): self
    {
        $assignment = new static();
        $assignment->tag_id = $tagId;
        return $assignment;
    }

    public function isForTag($id): bool
    {
        return $this->tag_id == $id;
    }

    public static function tableName(): string
    {
        return '{{%generic_tag_assignments}}';
    }
}