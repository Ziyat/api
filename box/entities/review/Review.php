<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace box\entities\review;

use box\entities\review\queries\ReviewsQuery;
use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
use paulzi\nestedsets\NestedSetsBehavior;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\web\UploadedFile;

/**
 * @property integer $id
 * @property string $title
 * @property string $text
 * @property integer $type
 * @property integer $item_id
 * @property integer $score
 * @property integer $lft
 * @property integer $rgt
 * @property integer $depth
 * @property integer $created_at
 * @property integer $created_by
 * @property integer $updated_at
 * @property integer $updated_by
 *
 * @property Review $parent
 * @property Review[] $parents
 * @property Review[] $children
 * @property Review $prev
 * @property Review $next
 *
 * @property Photo[] $photos
 *
 * @mixin NestedSetsBehavior
 */
class Review extends ActiveRecord
{
    public const TYPE_USER_PRODUCT = 10;
    public const TYPE_GENERIC_PRODUCT = 15;
    public const TYPE_USER = 20;

    public static function create($title, $text, $type, $item_id, $score): self
    {
        $review = new static();
        $review->title = $title;
        $review->text = $text;
        $review->type = $type;
        $review->item_id = $item_id;
        $review->score = $score ?: 0;

        return $review;
    }

    public function edit($title, $text, $type, $item_id, $score): void
    {
        $this->title = $title;
        $this->text = $text;
        $this->type = $type;
        $this->item_id = $item_id;
        $this->score = $score;
    }

    // Photos

    /**
     * @param UploadedFile $file
     */
    public function addPhoto(UploadedFile $file): void
    {
        $photos = $this->photos;
        $photos[] = Photo::create($file);
        $this->updatePhotos($photos);

    }

    /**
     * @param $id
     * @throws \DomainException
     */
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

    /**
     * @param $id
     * @throws \DomainException
     */
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

    /**
     * @param $id
     * @throws \DomainException
     */
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
    }

    public function getPhotos(): ActiveQuery
    {
        return $this->hasMany(Photo::class, ['review_id' => 'id'])->orderBy('sort');
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::class,
            BlameableBehavior::class,
            NestedSetsBehavior::class,
            [
                'class' => SaveRelationsBehavior::class,
                'relations' => ['photos']
            ]
        ];
    }

    public function fields()
    {
        return [
            'id' => 'id',
            'title' => 'title',
            'text' => 'text',
            'type' => 'type',
            'item_id' => 'item_id',
            'score' => 'score',
            'lft' => 'lft',
            'rgt' => 'rgt',
            'depth' => 'depth',
            'created_at' => 'created_at',
            'created_by' => 'created_by',
            'updated_at' => 'updated_at',
            'updated_by' => 'updated_by',
            'photos' => function () {
                return $this->responsePhotos();
            },
        ];
    }

    public static function tableName()
    {
        return '{{%reviews}}';
    }

    public function transactions(): array
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    public static function find()
    {
        return new ReviewsQuery(static::class);
    }

    private function responsePhotos(): array
    {
        $photos = [];
        foreach ($this->photos as $photo) {
            $photos[] = [
                'id' => $photo->id,
                'thumb' => $photo->getThumbFileUrl('file', 'thumb'),
                'large' => $photo->getThumbFileUrl('file', 'large'),
                'search' => $photo->getThumbFileUrl('file', 'search'),
                'original' => $photo->getUploadedFileUrl('file'),
            ];
        }
        return $photos;
    }
}