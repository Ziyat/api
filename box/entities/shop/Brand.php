<?php

namespace box\entities\shop;

use box\entities\behaviors\MetaBehavior;
use box\entities\Meta;
use yii\db\ActiveRecord;
use yii\web\UploadedFile;
use yiidreamteam\upload\ImageUploadBehavior;

/**
 * @property integer $id
 * @property string $name
 * @property string $slug
 * @property string $photo
 * @property Meta $meta
 */
class Brand extends ActiveRecord
{
    public $meta;

    public static function create($name, $slug, $photo, Meta $meta): self
    {
        $brand = new static();
        $brand->name = $name;
        $brand->slug = $slug;
        $brand->photo = $photo;
        $brand->meta = $meta;
        return $brand;
    }

    public function edit($name, $slug, $photo, Meta $meta)
    {
        $this->name = $name;
        $this->slug = $slug;
        $this->photo = $photo;
        $this->meta = $meta;
    }

    public function getSeoTitle()
    {
        return $this->meta->title ?: $this->name;
    }

    ##########################

    public static function tableName()
    {
        return '{{%brands}}';
    }

    public function behaviors(): array
    {
        return [
            MetaBehavior::class,
            [
                'class' => ImageUploadBehavior::class,
                'attribute' => 'photo',
                'thumbs' => [
                    'admin' => ['width' => 100, 'height' => 100],
                    'thumb' => ['width' => 480, 'height' => 480],
                ],
                'filePath' => '@staticPath/store/brand/[[id]]/[[filename]].[[extension]]',
                'fileUrl' => '@staticUrl/store/brand/[[id]]/[[filename]].[[extension]]',
                'thumbPath' => '@staticPath/cache/brand/[[id]]/[[profile]]_[[filename]].[[extension]]',
                'thumbUrl' => '@staticUrl/cache/brand/[[id]]/[[profile]]_[[filename]].[[extension]]',
            ]
        ];
    }
}