<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace box\entities\carousel;

use box\entities\generic\GenericProduct;
use box\entities\shop\Brand;
use box\entities\shop\product\Product;
use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;
use yiidreamteam\upload\ImageUploadBehavior;

/**
 * Carousel
 * @property integer $id
 * @property string $title
 * @property string $sub_title
 * @property string $description
 * @property string $text
 * @property integer $type
 * @property integer $item_id
 * @property integer $appointment
 * @property Image[] $images
 *
 * @mixin ImageUploadBehavior
 */
class Carousel extends ActiveRecord
{
    const TYPE_GENERIC_PRODUCT = 0;
    const TYPE_USER_PRODUCT = 1;
    const TYPE_BRAND = 2;

    const APPOINTMENT_NEWS = 0;

    public static function create($title, $subTitle, $description, $text, $type, $item_id, $appointment = null): self
    {
        $carousel = new static();
        $carousel->title = $title;
        $carousel->sub_title = $subTitle;
        $carousel->description = $description;
        $carousel->text = $text;
        $carousel->type = $type;
        $carousel->item_id = $item_id;
        $carousel->appointment = $appointment ?: self::APPOINTMENT_NEWS;
        return $carousel;
    }

    public function edit($title, $subTitle,$description, $text, $type, $item_id): void
    {
        $this->title = $title;
        $this->sub_title = $subTitle;
        $this->description = $description;
        $this->text = $text;
        $this->type = $type;
        $this->item_id = $item_id;
    }

    // Images

    public function addImage(UploadedFile $file): void
    {
        $images = $this->images;
        $images[] = Image::create($file);
        $this->updateImages($images);
    }

    public function removeImage($id): void
    {
        $images = $this->images;
        foreach ($images as $i => $image) {
            if ($image->isIdEqualTo($id)) {
                unset($images[$i]);
                $this->updateImages($images);
                return;
            }
        }
        throw new \DomainException('Image is not found.');
    }

    public function removeImages(): void
    {
        $this->updateImages([]);
    }

    public function moveImageUp($id): void
    {
        $images = $this->images;
        foreach ($images as $i => $image) {
            if ($image->isIdEqualTo($id)) {
                if ($prev = $images[$i - 1] ?? null) {
                    $images[$i - 1] = $image;
                    $images[$i] = $prev;
                    $this->updateImages($images);
                }
                return;
            }
        }
        throw new \DomainException('Image is not found.');
    }

    public function moveImageDown($id): void
    {
        $images = $this->images;
        foreach ($images as $i => $image) {
            if ($image->isIdEqualTo($id)) {
                if ($next = $images[$i + 1] ?? null) {
                    $images[$i] = $next;
                    $images[$i + 1] = $image;
                    $this->updateImages($images);
                }
                return;
            }
        }
        throw new \DomainException('Image is not found.');
    }

    private function updateImages(array $images): void
    {
        foreach ($images as $i => $image) {
            $image->setSort($i);
        }
        $this->images = $images;
    }

    public function getImages(): ActiveQuery
    {
        return $this->hasMany(Image::class, ['carousel_id' => 'id'])->orderBy('sort');
    }

    public static function tableName(): string
    {
        return '{{%carousels}}';
    }

    public function behaviors(): array
    {
        return [
            [
                'class' => SaveRelationsBehavior::class,
                'relations' => ['images'],
            ]
        ];
    }

    public function fields()
    {
        return [
            'id' => 'id',
            'title' => 'title',
            'description' => 'description',
            'text' => 'text',
            'type' => function (self $model) {
                return $model->getTypeName();
            },
            'item_id' =>'item_id',
            'item_photo' => function (self $model) {
                return $model->getMainPhotoItem();
            },
            'photos' => function(self $model){
                return ArrayHelper::getColumn($model->images,function(Image $image){
                    return $image->getThumbFileUrl('file');
                });
            },
        ];
    }


    protected function getTypeName()
    {
        switch ($this->type){
            case self::TYPE_GENERIC_PRODUCT:
                return 'generic_product';
            case self::TYPE_USER_PRODUCT:
                return 'user_product';
            case self::TYPE_BRAND:
                return 'brand';
            default:
                return 'undefined';
        }
    }


    protected function getMainPhotoItem()
    {
        switch ($this->type){
            case self::TYPE_GENERIC_PRODUCT:
                $product = GenericProduct::findOne($this->item_id);
                return !$product->mainPhoto ? null : $product->mainPhoto->getThumbFileUrl('file');
            case self::TYPE_USER_PRODUCT:
                $product = Product::findOne($this->item_id);
                return !$product->mainPhoto ? null : $product->mainPhoto->getThumbFileUrl('file');
            case self::TYPE_BRAND:
                $brand = Brand::findOne($this->item_id);
                return !$brand->photo ? null : $brand->getPhoto();
            default:
                return 'undefined';
        }
    }

    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }
}