<?php

namespace box\forms\shop\product;

use box\entities\shop\Brand;
use box\entities\shop\Characteristic;
use box\forms\CompositeForm;
use box\forms\manage\MetaForm;
use yii\helpers\ArrayHelper;

/**
 * @property MetaForm $meta
 * @property CategoriesForm $categories
 * @property ValueForm[] $values
 * @property TagsForm $tags
 */
class ProductCreateForm extends CompositeForm
{
    public $brandId;
    public $name;
    public $description;
    public $priceType;

    public function __construct($config = [])
    {
        $this->meta = new MetaForm();
        $this->categories = new CategoriesForm();
        $this->tags = new TagsForm();
        $this->values = array_map(function (Characteristic $characteristic) {
            return new ValueForm($characteristic);
        }, Characteristic::find()->orderBy('sort')->all());
        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            [['brandId', 'name', 'priceType'], 'required'],
            [['name'], 'string', 'max' => 255],
            [['brandId'], 'integer'],
            ['description', 'string'],
            ['priceType', 'string'],
        ];
    }

    public function brandsList(): array
    {
        return ArrayHelper::getColumn(Brand::find()->orderBy('name')->asArray()->all(), function ($model) {
            return [
                'id' => $model['id'],
                'name' => $model['name'],
            ];
        });
    }

    protected function internalForms(): array
    {
        return ['meta', 'categories', 'values', 'tags'];
    }
}