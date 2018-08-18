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
use yii\web\UploadedFile;
use yiidreamteam\upload\ImageUploadBehavior;

/**
 * Carousel
 * @property integer $id
 * @property string $title
 * @property string $description
 * @property string $text
 * @property integer $item_id
 * @property Image[] $images
 * @property Carousel $carousel
 *
 * @mixin ImageUploadBehavior
 */
class Item extends ActiveRecord
{
    public static function create($title, $description, $text, $item_id): self
    {
        $carousel = new static();
        $carousel->title = $title;
        $carousel->description = $description;
        $carousel->text = $text;
        $carousel->item_id = $item_id;
        return $carousel;
    }

    public function edit($title, $description, $text, $item_id): void
    {
        $this->title = $title;
        $this->description = $description;
        $this->text = $text;
        $this->item_id = $item_id;
    }


    public function isIdEqualTo($id): bool
    {
        return $this->id == $id;
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
        return $this->hasMany(Image::class, ['carousel_item_id' => 'id'])->orderBy('sort');
    }

    public function getCarousel()
    {
        return $this->hasOne(Carousel::class,['id' => 'carousel_id']);
    }

    public static function tableName(): string
    {
        return '{{%carousel_items}}';
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
            'item_id' => 'item_id',
            'item_img' => function(self $item){
                return $item->itemImg();
            },
            'images' => function(self $item){
                return $item->serializeImages();
            }
        ];
    }

    public function serializeImages()
    {
        $images = $this->images;
        if (!empty($images)) {
            foreach ($images as $image) {
                $imgUrls[] = [
                    'id' =>  $image->id,
                    'url' =>  $image->getThumbFileUrl('file')
                ];
            }
        }

        return !empty($imgUrls) ? $imgUrls : null;
    }

    public function itemImg()
    {
        switch ($this->carousel->type){
            case Carousel::TYPE_GENERIC_PRODUCT:
                $generic = GenericProduct::findOne($this->item_id);
                if($generic && $generic->mainPhoto){
                    return $generic->mainPhoto->getThumbFileUrl('file');
                }
                return null;
            case Carousel::TYPE_BRAND:
                $brand = Brand::findOne($this->item_id);
                return $brand ?: $brand->photo;
            case Carousel::TYPE_USER_PRODUCT:
                $product = Product::findOne($this->item_id);
                if($product && $product->mainPhoto){
                    return $product->mainPhoto->getThumbFileUrl('file');
                }
                return null;
            default:
                return null;
        }
    }

    /**
     * @return bool
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function beforeDelete(): bool
    {
        if (parent::beforeDelete()) {
            foreach ($this->images as $image) {
                $image->delete();
            }
            return true;
        }
        return false;
    }

}