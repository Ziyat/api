<?php

namespace box\forms\shop\product;

use box\entities\shop\Brand;
use box\entities\shop\Characteristic;
use box\entities\shop\product\PriceFix;
use box\entities\shop\product\Product;
use box\forms\CompositeForm;
use box\forms\manage\MetaForm;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;

/**
 * @property MetaForm $meta
 * @property CategoriesForm $categories
 * @property ValueForm[] $values
 * @property TagsForm $tags
 * @property PhotosForm $photos
 * @property PriceForm $price
 */
class ProductCreateForm extends CompositeForm
{
    public $brandId;
    public $name;
    public $description;
    public $priceType;
    public $quantity;

    public function __construct($config = [])
    {
        $this->meta = new MetaForm();
        $this->categories = new CategoriesForm();
        $this->tags = new TagsForm();
        $this->photos = new PhotosForm();
        $this->price = new PriceForm();
        $this->values = array_map(function (Characteristic $characteristic) {
            return new ValueForm($characteristic);
        }, Characteristic::find()->orderBy('sort')->all());
        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            [['brandId', 'name', 'priceType'], 'required'],
            [['name','description','priceType'], 'string', 'max' => 255],
            [['brandId','quantity'], 'integer'],
        ];
    }

    public function beforeValidate()
    {
        if(parent::beforeValidate())
        {
            if($dataFile = UploadedFile::getInstanceByName('data')){
                $data = file_get_contents($dataFile->tempName);
                $data = ArrayHelper::toArray(json_decode($data));
                $this->load($data,'');
                return true;
            }
        }
        return false;
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
        return ['meta', 'categories', 'values', 'tags', 'photos', 'price'];
    }
}