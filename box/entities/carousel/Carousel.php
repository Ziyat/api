<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace box\entities\carousel;

use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
use yii\db\ActiveRecord;
use yii\web\UploadedFile;
use yiidreamteam\upload\ImageUploadBehavior;

/**
 * Carousel
 * @property integer $id
 * @property string $title
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

    public static function create($title, $description, $text, $type, $item_id, $appointment = null): self
    {
        $carousel = new static();
        $carousel->title = $title;
        $carousel->description = $description;
        $carousel->text = $text;
        $carousel->type = $type;
        $carousel->item_id = $item_id;
        $carousel->appointment = $appointment ?: self::APPOINTMENT_NEWS;
        return $carousel;
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

    public function getImages()
    {
        return $this->hasMany(Image::class, ['carousel_id', 'id'])->orderBy('sort');
    }

    public function behaviors()
    {
        return [
            [
                'class' => SaveRelationsBehavior::class,
                'relations' => ['images'],
            ]
        ];
    }

    public static function tableName(): string
    {
        return '{{%carousels}}';
    }
}