<?php

namespace box\entities\generic;

use box\entities\shop\Brand;
use box\entities\shop\Category;
use box\entities\shop\Tag;
use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
use yii\behaviors\BlameableBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\web\UploadedFile;

/**
 * @property integer $id
 * @property integer $created_at
 * @property integer $created_by
 * @property integer $updated_at
 * @property integer $updated_by
 * @property string $name
 * @property string $description
 * @property integer $category_id
 * @property integer $brand_id
 * @property integer $rating
 * @property integer $main_photo_id
 *
 * @property Brand $brand
 * @property Category $category
 * @property RelatedAssignment[] $relatedAssignments
 * @property GenericCategoryAssignment[] $categoryAssignments
 * @property Category[] $categories
 * @property Tag[] $tags
 * @property GenericValue[] $values
 * @property GenericTagAssignment[] $tagAssignments
 * @property GenericPhoto[] $photos
 * @property GenericModification[] $modifications
 * @property GenericPhoto $mainPhoto
 */
class GenericProduct extends ActiveRecord
{
    public static function create($brandId, $categoryId, $name, $description): self
    {
        $product = new static();
        $product->brand_id = $brandId;
        $product->category_id = $categoryId;
        $product->name = $name;
        $product->description = $description;
        $product->created_at = time();
        $product->updated_at = time();
        return $product;
    }


    public function edit($brandId, $name, $description): void
    {
        $this->brand_id = $brandId;
        $this->name = $name;
        $this->description = $description;
    }

    public function changeMainCategory($categoryId): void
    {
        $this->category_id = $categoryId;
    }


    public function setValue($id, $value): void
    {
        $values = $this->values;
        foreach ($values as $val) {
            if ($val->isForCharacteristic($id)) {
                $val->change($value);
                $this->values = $values;
                return;
            }
        }
        $values[] = GenericValue::create($id, $value);
        $this->values = $values;
    }

    public function setModification($characteristic_id, $value, $main_photo_id, $modificationId = null): void
    {
        $modifications = $this->modifications;
        foreach ($modifications as $modification) {
            if ($modification->isForCharacteristic($characteristic_id, $modificationId)) {
                $modification->change($value, $main_photo_id);
                $this->modifications = $modifications;
                return;
            }
        }
        $modifications[] = GenericModification::create($characteristic_id, $value, $main_photo_id);
        $this->modifications = $modifications;
    }

    public function getValue($id): GenericValue
    {
        $values = $this->values;
        foreach ($values as $val) {
            if ($val->isForCharacteristic($id)) {
                return $val;
            }
        }
        return GenericValue::blank($id);
    }



    // Categories

    public function assignCategory($id): void
    {
        $assignments = $this->categoryAssignments;
        foreach ($assignments as $assignment) {
            if ($assignment->isForCategory($id)) {
                return;
            }
        }
        $assignments[] = GenericCategoryAssignment::create($id);
        $this->categoryAssignments = $assignments;
    }

    public function revokeCategory($id): void
    {
        $assignments = $this->categoryAssignments;
        foreach ($assignments as $i => $assignment) {
            if ($assignment->isForCategory($id)) {
                unset($assignments[$i]);
                $this->categoryAssignments = $assignments;
                return;
            }
        }
        throw new \DomainException('Assignment is not found.');
    }

    public function revokeCategories(): void
    {
        $this->categoryAssignments = [];
    }

    // Tags

    public function assignTag($id): void
    {
        $assignments = $this->tagAssignments;
        foreach ($assignments as $assignment) {
            if ($assignment->isForTag($id)) {
                return;
            }
        }
        $assignments[] = GenericTagAssignment::create($id);
        $this->tagAssignments = $assignments;
    }

    public function revokeTag($id): void
    {
        $assignments = $this->tagAssignments;
        foreach ($assignments as $i => $assignment) {
            if ($assignment->isForTag($id)) {
                unset($assignments[$i]);
                $this->tagAssignments = $assignments;
                return;
            }
        }
        throw new \DomainException('Assignment is not found.');
    }

    public function revokeTags(): void
    {
        $this->tagAssignments = [];
    }

    // Photos

    public function addPhoto(UploadedFile $file): void
    {
        $photos = $this->photos;
        $photos[] = GenericPhoto::create($file);
        $this->updatePhotos($photos);
    }

    public function removePhoto($id): void
    {
        $photos = $this->photos;
        foreach ($photos as $i => $photo) {
            if ($photo->isIdEqualTo($id)) {
                unset($photos[$i]);
                $this->updatePhotos($photos);
                return;
            }
        }
        throw new \DomainException('Photo is not found.');
    }

    public function removePhotos(): void
    {
        $this->updatePhotos([]);
    }

    public function movePhotoUp($id): void
    {
        $photos = $this->photos;
        foreach ($photos as $i => $photo) {
            if ($photo->isIdEqualTo($id)) {
                if ($prev = $photos[$i - 1] ?? null) {
                    $photos[$i - 1] = $photo;
                    $photos[$i] = $prev;
                    $this->updatePhotos($photos);
                }
                return;
            }
        }
        throw new \DomainException('Photo is not found.');
    }

    public function movePhotoDown($id): void
    {
        $photos = $this->photos;
        foreach ($photos as $i => $photo) {
            if ($photo->isIdEqualTo($id)) {
                if ($next = $photos[$i + 1] ?? null) {
                    $photos[$i] = $next;
                    $photos[$i + 1] = $photo;
                    $this->updatePhotos($photos);
                }
                return;
            }
        }
        throw new \DomainException('Photo is not found.');
    }

    private function updatePhotos(array $photos): void
    {
        foreach ($photos as $i => $photo) {
            $photo->setSort($i);
        }
        $this->photos = $photos;
        $this->populateRelation('mainPhoto', reset($photos));
    }


    // Reviews

    /*public function addReview($userId, $vote, $text): void
    {
        $reviews = $this->reviews;
        $reviews[] = Review::create($userId, $vote, $text);
        $this->updateReviews($reviews);
    }

    public function editReview($id, $vote, $text): void
    {
        $this->doWithReview($id, function (Review $review) use ($vote, $text) {
            $review->edit($vote, $text);
        });
    }

    public function activateReview($id): void
    {
        $this->doWithReview($id, function (Review $review) {
            $review->activate();
        });
    }

    public function draftReview($id): void
    {
        $this->doWithReview($id, function (Review $review) {
            $review->draft();
        });
    }

    private function doWithReview($id, callable $callback): void
    {
        $reviews = $this->reviews;
        foreach ($reviews as $review) {
            if ($review->isIdEqualTo($id)) {
                $callback($review);
                $this->updateReviews($reviews);
                return;
            }
        }
        throw new \DomainException('Review is not found.');
    }

    public function removeReview($id): void
    {
        $reviews = $this->reviews;
        foreach ($reviews as $i => $review) {
            if ($review->isIdEqualTo($id)) {
                unset($reviews[$i]);
                $this->updateReviews($reviews);
                return;
            }
        }
        throw new \DomainException('Review is not found.');
    }

    private function updateReviews(array $reviews): void
    {
        $amount = 0;
        $total = 0;

        foreach ($reviews as $review) {
            if ($review->isActive()) {
                $amount++;
                $total += $review->getRating();
            }
        }

        $this->reviews = $reviews;
        $this->rating = $amount ? $total / $amount : null;
    }*/

    ##########################

    public function getBrand(): ActiveQuery
    {
        return $this->hasOne(Brand::class, ['id' => 'brand_id']);
    }

    public function getCategory(): ActiveQuery
    {
        return $this->hasOne(Category::class, ['id' => 'category_id']);
    }

    public function getCategoryAssignments(): ActiveQuery
    {
        return $this->hasMany(GenericCategoryAssignment::class, ['generic_product_id' => 'id']);
    }


    public function getCategories(): ActiveQuery
    {
        return $this->hasMany(Category::class, ['id' => 'category_id'])->via('categoryAssignments');
    }

    public function getTagAssignments(): ActiveQuery
    {
        return $this->hasMany(GenericTagAssignment::class, ['generic_product_id' => 'id']);
    }

    public function getTags(): ActiveQuery
    {
        return $this->hasMany(Tag::class, ['id' => 'tag_id'])->via('tagAssignments');
    }

    public function getModifications(): ActiveQuery
    {
        return $this->hasMany(GenericModification::class, ['generic_product_id' => 'id']);
    }

    public function getValues(): ActiveQuery
    {
        return $this->hasMany(GenericValue::class, ['generic_product_id' => 'id']);
    }

    public function getPhotos(): ActiveQuery
    {
        return $this->hasMany(GenericPhoto::class, ['generic_product_id' => 'id'])->orderBy('sort');
    }

    public function getMainPhoto(): ActiveQuery
    {
        return $this->hasOne(GenericPhoto::class, ['id' => 'main_photo_id']);
    }

//    public function getRelatedAssignments(): ActiveQuery
//    {
//        return $this->hasMany(RelatedAssignment::class, ['product_id' => 'id']);
//    }
//
//    public function getRelateds(): ActiveQuery
//    {
//        return $this->hasMany(Product::class, ['id' => 'related_id'])->via('relatedAssignments');
//    }
//
//    public function getReviews(): ActiveQuery
//    {
//        return $this->hasMany(Review::class, ['product_id' => 'id']);
//    }
//
//    public function getWishlistItems(): ActiveQuery
//    {
//        return $this->hasMany(WishlistItem::class, ['product_id' => 'id']);
//    }

    ##########################

    public static function tableName(): string
    {
        return '{{%generic_products}}';
    }

    public function behaviors(): array
    {
        return [
            BlameableBehavior::class,
            [
                'class' => SaveRelationsBehavior::class,
                'relations' => ['categoryAssignments', 'tagAssignments', 'values', 'photos', 'prices', 'modifications'],
            ],
        ];
    }

    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    /**
     * @return bool
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function beforeDelete(): bool
    {
        if (parent::beforeDelete()) {
            foreach ($this->photos as $photo) {
                $photo->delete();
            }
            return true;
        }
        return false;
    }

    public function afterSave($insert, $changedAttributes): void
    {
        $related = $this->getRelatedRecords();
        parent::afterSave($insert, $changedAttributes);
        if (array_key_exists('mainPhoto', $related)) {
            $this->updateAttributes(['main_photo_id' => $related['mainPhoto'] ? $related['mainPhoto']->id : null]);
        }
    }

    public function fields()
    {
        return [
            "id" => "id",
            "category_id" => "category_id",
            "brand" => function(self $model){
                return [
                    'id' => $model->brand_id,
                    'photo' => $model->brand->getPhoto(),
                ];
            },
            "name" => "name",
            "description" => "description",

            "photo" => function () {
                return $this->responseMainPhotos();
            },
            "photos" => function () {
                return $this->responsePhotos();
            },
            "characteristics" => function () {
                $result = [];
                foreach ($this->values as $value) {
                    $result[] = [
                        'id' => $value->characteristic->id,
                        'name' => $value->characteristic->name,
                        'value' => $value->value,
                    ];
                }
                return $result;
            },
            "modifications" => function () {
                $result = [];
                foreach ($this->modifications as $k => $modification) {
                    $result[$k] = [
                        'id' => $modification->id,
                        'characteristic_id' => $modification->characteristic->id,
                        'characteristic' => $modification->characteristic->name,
                        'value' => $modification->value,
                        'photo' => $modification->mainPhoto ? $modification->mainPhoto->getThumbFileUrl('file', 'thumb') : null
                    ];
                }
                return $result;
            },
            "tags" => function () {
                return $this->tags;
            },
            "rating" => "rating",
            "created_at" => "created_at",
            "updated_at" => "updated_at",
        ];
    }

    public function responseMainPhotos(): array
    {
        $mainPhoto = [];
        if ($main = $this->mainPhoto) {
            $mainPhoto = [
                'id' => $main->id,
                'thumb' => $main->getThumbFileUrl('file', 'thumb'),
                'large' => $main->getThumbFileUrl('file', 'large'),
                'search' => $main->getThumbFileUrl('file', 'search'),
                'original' => $main->getUploadedFileUrl('file'),
            ];
        }
        return $mainPhoto;
    }

    public function responsePhotos(): array
    {
        $photos = [];
        foreach ($this->photos as $photo) {
            if ($photo->id != $this->main_photo_id) {
                $photos[] = [
                    'id' => $photo->id,
                    'thumb' => $photo->getThumbFileUrl('file', 'thumb'),
                    'large' => $photo->getThumbFileUrl('file', 'large'),
                    'search' => $photo->getThumbFileUrl('file', 'search'),
                    'original' => $photo->getUploadedFileUrl('file'),
                ];
            }
        }
        return $photos;
    }


}