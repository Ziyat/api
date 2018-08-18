<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace box\entities\carousel;

use box\forms\carousel\ImageForm;
use box\repositories\NotFoundException;
use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
use yii\db\ActiveRecord;
use yii\web\UploadedFile;
use yiidreamteam\upload\ImageUploadBehavior;

/**
 * Carousel
 * @property integer $id
 * @property string $title
 * @property integer $type
 * @property integer $template_id
 * @property string $sub_title
 * @property integer $status
 *
 * @property Item[] $items
 *
 * @mixin ImageUploadBehavior
 */
class Carousel extends ActiveRecord
{
    const TYPE_GENERIC_PRODUCT = 0;
    const TYPE_USER_PRODUCT = 1;
    const TYPE_BRAND = 2;

    public static function create($title, $subTitle, $type, $template_id): self
    {
        $carousel = new static();
        $carousel->title = $title;
        $carousel->sub_title = $subTitle;
        $carousel->type = $type;
        $carousel->template_id = $template_id;
        return $carousel;
    }

    public function edit($title, $subTitle, $type, $template_id): void
    {
        $this->title = $title;
        $this->sub_title = $subTitle;
        $this->type = $type;
        $this->template_id = $template_id;
    }

    // Items

    public function addItem($title, $description, $text, $item_id, ImageForm $images)
    {
        $item = Item::create($title, $description, $text, $item_id);
        foreach ($images->files as $file) {
            $item->addImage($file);
        }
        $items = $this->items;
        $items[] = $item;
        $this->updateItem($items);
    }

    public function editItem($id, $title, $description, $text, $item_id)
    {
        $items = $this->items;
        foreach ($items as $k => $item) {
            if ($item->isIdEqualTo($id)) {
                $items[$k]->edit($title, $description, $text, $item_id);
                $this->updateItem($items);
                return;
            }
        }
    }

    /**
     * @param $id
     * @throws NotFoundException
     */
    public function removeItem($id)
    {
        $items = $this->items;
        foreach ($items as $k => $item) {

            if ($item->isIdEqualTo($id)) {
                unset($items[$k]);
                $this->updateItem($items);
                return;
            }
        }
        throw new NotFoundException('Item not found.');
    }

    /**
     * @param $id
     * @param UploadedFile $file
     * @throws NotFoundException
     */

    public function addItemImage($item_id, UploadedFile $file)
    {
        $items = $this->items;
        foreach ($items as $k => $item) {
            if ($item->isIdEqualTo($item_id)) {
                $item->addImage($file);
                if($item->save()){
                    $items[$k] = $item;
                    $this->updateItem($items);
                    return;
                }
                throw new \DomainException('save item image error');
            }
        }
        throw new NotFoundException('Item not found.');
    }

    /**
     * @param $item_id
     * @param $image_id
     * @throws NotFoundException
     */
    public function removeItemImage($item_id, $image_id)
    {
        $items = $this->items;
        foreach ($items as $k => $item) {
            if ($item->isIdEqualTo($item_id)) {
                $item->removeImage($image_id);
                if($item->save()){
                    $items[$k] = $item;
                    $this->updateItem($items);
                    return;
                }
                throw new \DomainException('save item image error');
            }
        }
        throw new NotFoundException('Item not found.');
    }

    private function updateItem(array $items): void
    {
        $this->items = $items;
    }

    public static function tableName(): string
    {
        return '{{%carousels}}';
    }

    public function getItems()
    {
        return $this->hasMany(Item::class, ['carousel_id' => 'id']);
    }

    public function fields()
    {
        return [
            'id' => 'id',
            'title' => 'title',
            'type' => 'type',
            'template_id' => 'template_id',
            'sub_title' => 'sub_title',
            'status' => 'status',
            'items' => function (self $carousel) {
                return $carousel->items;
            }
        ];
    }

    public function behaviors(): array
    {
        return [
            [
                'class' => SaveRelationsBehavior::class,
                'relations' => ['items'],
            ]
        ];
    }

    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }
}