<?php

namespace box\entities\shop\product;

use box\entities\shop\product\queries\ProductQuery;
use box\forms\shop\product\ModificationForm;
use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
use box\entities\behaviors\MetaBehavior;
use box\entities\Meta;
use box\entities\shop\Brand;
use box\entities\shop\Category;
use box\entities\shop\Tag;
use yii\behaviors\BlameableBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;
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
 * @property string $price_type
 * @property integer $rating
 * @property integer $status
 * @property integer $quantity
 * @property integer $main_photo_id
 *
 * @property Meta $meta
 * @property Brand $brand
 * @property Category $category
 * @property RelatedAssignment[] $relatedAssignments
 * @property CategoryAssignment[] $categoryAssignments
 * @property Category[] $categories
 * @property Tag[] $tags
 * @property Value[] $values
 * @property TagAssignment[] $tagAssignments
 * @property Photo[] $photos
 * @property Price $prices
 * @property Price $price
 * @property Modification[] $modifications
 * @property Photo $mainPhoto
 */
class Product extends ActiveRecord
{

    const STATUS_DRAFT = 0;
    const STATUS_ACTIVE = 1;

    const PRICE_TYPE_AUCTION = 'auction';
    const PRICE_TYPE_BARGAIN = 'bargain';
    const PRICE_TYPE_FIX = 'fix';

    public $meta;

    public static function create($brandId, $categoryId, $name, $description, Meta $meta): self
    {
        $product = new static();
        $product->brand_id = $brandId;
        $product->category_id = $categoryId;
        $product->name = $name;
        $product->description = $description;
        $product->meta = $meta;
        $product->status = self::STATUS_DRAFT;
        $product->created_at = time();
        $product->updated_at = time();
        return $product;
    }


    public function isFixPrice()
    {
        return $this->price_type == $this::PRICE_TYPE_FIX;
    }

    public function isAuctionPrice()
    {
        return $this->price_type == $this::PRICE_TYPE_AUCTION;
    }

    public function isBargainPrice()
    {
        return $this->price_type == $this::PRICE_TYPE_BARGAIN;
    }


    public function setPriceType($price_type): void
    {
        $this->price_type = $price_type;
    }

    public function setPrice($current, $end, $max, $deadline, $buyNow): void
    {
        $prices = $this->prices;
        foreach ($prices as $price){
            if($price->isEqual($current, $end, $max, $deadline, $buyNow)) return;
        }
        $prices[] = Price::create($current, $end, $max, $deadline, $buyNow);
        $this->prices = $prices;
    }

//    public function changeQuantity($quantity): void
//    {
//        if ($this->modifications) {
//            throw new \DomainException('Change modifications quantity.');
//        }
//        $this->setQuantity($quantity);
//    }

    public function edit($brandId, $name, $description, Meta $meta): void
    {
        $this->brand_id = $brandId;
        $this->name = $name;
        $this->description = $description;
        $this->meta = $meta;
    }

    public function changeMainCategory($categoryId): void
    {
        $this->category_id = $categoryId;
    }

    public function activate(): void
    {
        if ($this->isActive()) {
            throw new \DomainException('Product is already active.');
        }
        $this->status = self::STATUS_ACTIVE;
    }

    public function draft(): void
    {
        if ($this->isDraft()) {
            throw new \DomainException('Product is already draft.');
        }
        $this->status = self::STATUS_DRAFT;
    }

    public function isActive(): bool
    {
        return $this->status == self::STATUS_ACTIVE;
    }


    public function isDraft(): bool
    {
        return $this->status == self::STATUS_DRAFT;
    }

//    public function isAvailable(): bool
//    {
//        return $this->quantity > 0;
//    }

    /*public function canChangeQuantity(): bool
    {
        return !$this->modifications;
    }

    public function canBeCheckout($modificationId, $quantity): bool
    {
        if ($modificationId) {
            return $quantity <= $this->getModification($modificationId)->quantity;
        }
        return $quantity <= $this->quantity;
    }*/

    /*public function checkout($modificationId, $quantity): void
    {
        if ($modificationId) {
            $modifications = $this->modifications;
            foreach ($modifications as $i => $modification) {
                if ($modification->isIdEqualTo($modificationId)) {
                    $modification->checkout($quantity);
                    $this->updateModifications($modifications);
                    return;
                }
            }
        }
        if ($quantity > $this->quantity) {
            throw new \DomainException('Only ' . $this->quantity . ' items are available.');
        }
        $this->setQuantity($this->quantity - 1);
    }*/

    public function setQuantity($quantity = null): void
    {
        if (empty($this->modifications)) {
            $this->quantity = $quantity ?? 1;

        } else {
            $this->quantity = array_sum(array_map(function (Modification $modification) {
                return $modification->quantity;
            }, $this->modifications));
        }

    }

    public function getSeoTile(): string
    {
        return $this->meta->title ?: $this->name;
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
        $values[] = Value::create($id, $value);
        $this->values = $values;
    }


    public function setModification($characteristic_id, $value, $price, $quantity, $main_photo_id,$modificationId=null): void
    {
        $modifications = $this->modifications;
        foreach ($modifications as $modification) {
            if ($modification->isForCharacteristic($characteristic_id,$modificationId)) {
                $modification->change($value, $price, $main_photo_id, $quantity);
                $this->modifications = $modifications;
                return;
            }
        }
        $modifications[] = Modification::create($characteristic_id, $value, $price, $quantity, $main_photo_id);
        $this->modifications = $modifications;
    }

    public function getValue($id): Value
    {
        $values = $this->values;
        foreach ($values as $val) {
            if ($val->isForCharacteristic($id)) {
                return $val;
            }
        }
        return Value::blank($id);
    }

    // Modification

    /*public function getModification($id): Modification
    {
        foreach ($this->modifications as $modification) {
            if ($modification->isIdEqualTo($id)) {
                return $modification;
            }
        }
        throw new \DomainException('Modification is not found.');
    }

    public function getModificationPrice($id): int
    {
        foreach ($this->modifications as $modification) {
            if ($modification->isIdEqualTo($id)) {
                return $modification->price ?: $this->price_new;
            }
        }
        throw new \DomainException('Modification is not found.');
    }

    public function addModification($code, $name, $price, $quantity): void
    {
        $modifications = $this->modifications;
        foreach ($modifications as $modification) {
            if ($modification->isCodeEqualTo($code)) {
                throw new \DomainException('Modification already exists.');
            }
        }
        $modifications[] = Modification::create($code, $name, $price, $quantity);
        $this->updateModifications($modifications);
    }

    public function editModification($id, $code, $name, $price, $quantity): void
    {
        $modifications = $this->modifications;
        foreach ($modifications as $i => $modification) {
            if ($modification->isIdEqualTo($id)) {
                $modification->edit($code, $name, $price, $quantity);
                $this->updateModifications($modifications);
                return;
            }
        }
        throw new \DomainException('Modification is not found.');
    }

    public function removeModification($id): void
    {
        $modifications = $this->modifications;
        foreach ($modifications as $i => $modification) {
            if ($modification->isIdEqualTo($id)) {
                unset($modifications[$i]);
                $this->updateModifications($modifications);
                return;
            }
        }
        throw new \DomainException('Modification is not found.');
    }

    private function updateModifications(array $modifications): void
    {
        $this->modifications = $modifications;
        $this->setQuantity(array_sum(array_map(function (Modification $modification) {
            return $modification->quantity;
        }, $this->modifications)));
    }*/

    // Categories

    public function assignCategory($id): void
    {
        $assignments = $this->categoryAssignments;
        foreach ($assignments as $assignment) {
            if ($assignment->isForCategory($id)) {
                return;
            }
        }
        $assignments[] = CategoryAssignment::create($id);
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
        $assignments[] = TagAssignment::create($id);
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
        $photos[] = Photo::create($file);
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

    // Related products

//    public function assignRelatedProduct($id): void
//    {
//        $assignments = $this->relatedAssignments;
//        foreach ($assignments as $assignment) {
//            if ($assignment->isForProduct($id)) {
//                return;
//            }
//        }
//        $assignments[] = RelatedAssignment::create($id);
//        $this->relatedAssignments = $assignments;
//    }
//
//    public function revokeRelatedProduct($id): void
//    {
//        $assignments = $this->relatedAssignments;
//        foreach ($assignments as $i => $assignment) {
//            if ($assignment->isForProduct($id)) {
//                unset($assignments[$i]);
//                $this->relatedAssignments = $assignments;
//                return;
//            }
//        }
//        throw new \DomainException('Assignment is not found.');
//    }

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
        return $this->hasMany(CategoryAssignment::class, ['product_id' => 'id']);
    }

    public function getPrices(): ActiveQuery
    {
        return $this->hasMany(Price::class, ['product_id' => 'id'])->orderBy(['created_at' => SORT_DESC]);
    }

    public function getPrice(): ActiveQuery
    {
        return $this->hasOne(Price::class, ['product_id' => 'id'])->orderBy(['created_at' => SORT_DESC]);
    }

    public function getCategories(): ActiveQuery
    {
        return $this->hasMany(Category::class, ['id' => 'category_id'])->via('categoryAssignments');
    }

    public function getTagAssignments(): ActiveQuery
    {
        return $this->hasMany(TagAssignment::class, ['product_id' => 'id']);
    }

    public function getTags(): ActiveQuery
    {
        return $this->hasMany(Tag::class, ['id' => 'tag_id'])->via('tagAssignments');
    }

    public function getModifications(): ActiveQuery
    {
        return $this->hasMany(Modification::class, ['product_id' => 'id']);
    }

    public function getValues(): ActiveQuery
    {
        return $this->hasMany(Value::class, ['product_id' => 'id']);
    }

    public function getPhotos(): ActiveQuery
    {
        return $this->hasMany(Photo::class, ['product_id' => 'id'])->orderBy('sort');
    }

    public function getMainPhoto(): ActiveQuery
    {
        return $this->hasOne(Photo::class, ['id' => 'main_photo_id']);
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
        return '{{%products}}';
    }

    public function behaviors(): array
    {
        return [
            MetaBehavior::class,
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

    public static function find(): ProductQuery
    {
        return new ProductQuery(static::class);
    }


    public function fields()
    {
        return [
            "id" => "id",
            "category_id" => "category_id",
            "brand_id" => "brand_id",
            "status" => "status",
            "name" => "name",
            "description" => "description",
            "quantity" => "quantity",

            "photo" => function () {
                return $this->responseMainPhotos();
            },
            "photos" => function () {
                return $this->responsePhotos();
            },

            "price" => function () {
                return $this->responsePrice();
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
                        'price' => $modification->price,
                        'quantity' => $modification->quantity,
                        'main_photo_id' => $modification->mainPhoto ? $modification->mainPhoto->id : null,
                        'photo' => $modification->mainPhoto ? $modification->mainPhoto->getThumbFileUrl('file', 'thumb') : null
                    ];
                }
                return $result;
            },
            "tags" => function () {
                return $this->tags;
            },
            "price_type" => "price_type",
            "rating" => "rating",
            "meta_json" => function () {
                return $this->meta;
            },
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

    public function responsePrice()
    {
        if (!empty($prices = $this->prices)) {
            $current = array_shift($prices);
            return [
                'current' => [
                    $this->price_type == $this::PRICE_TYPE_BARGAIN
                        ? 'buyNow'
                        : 'price' => $current->current,
                    'max' => $current->max,
                    'end' => $current->end
                ],
                'old' => $prices
            ];
        }
        return [];
    }

}