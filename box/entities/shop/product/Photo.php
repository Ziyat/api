<?php

namespace box\entities\shop\product;

use box\components\UploadBehavior;
use yii\db\ActiveRecord;
use yii\web\UploadedFile;
use yiidreamteam\upload\ImageUploadBehavior;

/**
 * @property integer $id
 * @property string $file
 * @property integer $sort
 *
 * @mixin ImageUploadBehavior
 */
class Photo extends ActiveRecord
{
    public static function create(UploadedFile $file): self
    {
        $photo = new static();
        $photo->file = $file;
        return $photo;
    }

    public function setSort($sort): void
    {
        $this->sort = $sort;
    }

    public function isIdEqualTo($id): bool
    {
        return $this->id == $id;
    }

    public static function tableName(): string
    {
        return '{{%photos}}';
    }

    public function behaviors(): array
    {
        return [
            [
                'class' => UploadBehavior::class,
                'attribute' => 'file',
                'createThumbsOnRequest' => true,
                'filePath' => '@staticPath/store/products/[[attribute_product_id]]/[[id]].[[extension]]',
                'fileUrl' => '@staticUrl/store/products/[[attribute_product_id]]/[[id]].[[extension]]',
                'thumbPath' => '@staticPath/cache/products/[[attribute_product_id]]/[[profile]]_[[id]].[[extension]]',
                'thumbUrl' => '@staticUrl/cache/products/[[attribute_product_id]]/[[profile]]_[[id]].[[extension]]',
                'thumbs' => [
                    'thumb' => ['width' => 450, 'height' => 675],
                    'large' => ['width' => 300, 'height' => 450],
                    'search' => ['width' => 100, 'height' => 150],
                ],
            ],
        ];
    }
}