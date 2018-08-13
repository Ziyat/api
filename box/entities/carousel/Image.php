<?php

namespace box\entities\carousel;

use yii\db\ActiveRecord;
use yii\web\UploadedFile;
use yiidreamteam\upload\ImageUploadBehavior;

/**
 * Class Image
 *
 * @property integer $id
 * @property string $file
 * @property integer $sort
 *
 * @mixin ImageUploadBehavior
 */
class Image extends ActiveRecord
{
    public static function create(UploadedFile $file): self
    {
        $image = new static();
        $image->file = $file;
        return $image;
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
        return '{{%carousel_images}}';
    }

    public function behaviors(): array
    {
        return [
            [
                'class' => ImageUploadBehavior::class,
                'attribute' => 'file',
                'createThumbsOnRequest' => true,
                'filePath' => '@staticPath/store/carousel_images/[[attribute_carousel_id]]/[[id]].[[extension]]',
                'fileUrl' => '@staticUrl/store/carousel_images/[[attribute_carousel_id]]/[[id]].[[extension]]',
                'thumbPath' => '@staticPath/cache/carousel_images/[[attribute_carousel_id]]/[[profile]]_[[id]].[[extension]]',
                'thumbUrl' => '@staticUrl/cache/carousel_images/[[attribute_carousel_id]]/[[profile]]_[[id]].[[extension]]',
                'thumbs' => [
                    'thumb' => ['width' => 450, 'height' => 675],
                    'large' => ['width' => 300, 'height' => 450],
                    'search' => ['width' => 100, 'height' => 150],
                ],
            ],
        ];
    }
}