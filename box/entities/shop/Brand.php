<?php

namespace box\entities\shop;

use box\entities\behaviors\MetaBehavior;
use box\entities\generic\GenericProduct;
use box\entities\Meta;
use box\entities\shop\product\Product;
use box\entities\user\User;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yiidreamteam\upload\ImageUploadBehavior;

/**
 * @property integer $id
 * @property string $name
 * @property string $slug
 * @property string $photo
 * @property Meta $meta
 * @property User[] $users
 *
 * @mixin ImageUploadBehavior
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

    public function getUserProducts(): ActiveQuery
    {
        return $this->hasMany(Product::class, ['brand_id' => 'id'])
            ->andWhere(['status' => [Product::STATUS_ACTIVE,Product::STATUS_MARKET]]);
    }

    public function getGenericProducts(): ActiveQuery
    {
        return $this->hasMany(GenericProduct::class, ['brand_id' => 'id'])
            ->andWhere(['status' => [Product::STATUS_ACTIVE,Product::STATUS_MARKET]]);
    }

    public function getUsers(): ActiveQuery
    {
        return $this->hasMany(User::class,['id' => 'created_by'])
            ->via('userProducts');
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
                    'admin' => ['width' => 120, 'height' => 100],
                    'thumb' => ['width' => 600, 'height' => 480],
                ],
                'filePath' => '@staticPath/store/brands/[[id]]/[[id]].[[extension]]',
                'fileUrl' => '@staticUrl/store/brands/[[id]]/[[id]].[[extension]]',
                'thumbPath' => '@staticPath/cache/brands/[[id]]/[[profile]]_[[id]].[[extension]]',
                'thumbUrl' => '@staticUrl/cache/brands/[[id]]/[[profile]]_[[id]].[[extension]]',
            ]
        ];
    }

    public function fields()
    {
        return [
            'id' => 'id',
            'name' => 'name',
            'slug' => 'slug',
            'photo' => function (self $model) {
                return $model->getPhoto();
            },
            'meta' => 'meta'
        ];
    }

    /**
     * @method getPhoto()
     * @param string $profile
     * @return null|string
     */
    public function getPhoto($profile = 'thumb')
    {
        return $this->getThumbFileUrl('photo', $profile, \Yii::getAlias('@staticUrl') . '/empty/no-photo.jpg');
    }
}