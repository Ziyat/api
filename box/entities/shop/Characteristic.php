<?php

namespace box\entities\shop;

use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\Json;
use yii\helpers\VarDumper;

/**
 * @property integer $id
 * @property string $name
 *
 * @property CharacteristicAssignment[] $assignments
 * @property Category[] $categories
 */
class Characteristic extends ActiveRecord
{

    public static function create($name): self
    {
        $object = new static();
        $object->name = $name;
        return $object;
    }

    public function edit($name): void
    {
        $this->name = $name;
    }


    public function assignCategory($id, $variants): void
    {
        $assignments = $this->assignments;

        foreach ($assignments as $k => $assignment) {
            if ($assignment->isForCategory($id)) {
                $assignments[$k]->variants = $variants;
                $assignments[$k]->variants_json = Json::encode(array_filter($variants));
                $this->assignments = $assignments;
                return;
            }
        }

        $assignments[] = CharacteristicAssignment::create($id, $variants);
        $this->assignments = $assignments;


    }

    public function getAssignments(): ActiveQuery
    {
        return $this->hasMany(CharacteristicAssignment::class, ['characteristic_id' => 'id']);
    }

    public function getCategories()
    {
        return $this->hasMany(Category::class, ['id' => 'category_id'])
            ->via('assignments');
    }

    public function fields()
    {
        return [
            'id' => 'id',
            'name' => 'name',
            'variants' => function(self $model){return $model->assignments[0]->variants;},
            'categories' => function(self $model){return $model->categories;},
        ];
    }


    public static function tableName(): string
    {
        return '{{%characteristics}}';
    }


    public function behaviors()
    {
        return [
            [
                'class' => SaveRelationsBehavior::class,
                'relations' => ['assignments'],
            ],
        ];

    }
}