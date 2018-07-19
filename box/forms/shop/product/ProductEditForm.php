<?php

namespace box\forms\shop\product;

use box\entities\shop\Brand;
use box\entities\shop\product\Product;
use box\entities\shop\product\Value;
use box\forms\CompositeForm;
use box\forms\manage\MetaForm;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;

/**
 * @property MetaForm $meta
 * @property CategoriesForm $categories
 * @property ValueForm[] $characteristics
 * @property TagsForm $tags
 * @property PhotosForm $photos
 * @property PriceForm $price
 * @property ModificationForm[] $modifications
 */
class ProductEditForm extends CompositeForm
{
    public $brandId;
    public $name;
    public $description;
    public $priceType;
    public $quantity;

    private $_product;

    public function __construct(Product $product, $config = [])
    {
        $this->brandId = $product->brand_id;
        $this->name = $product->name;
        $this->description = $product->description;
        $this->priceType = $product->price_type;
        $this->quantity = $product->quantity;
        $this->meta = new MetaForm($product->meta);
        $this->categories = new CategoriesForm($product);
        $this->tags = new TagsForm($product);
        $this->price = new PriceForm($product);
        if ($product->values) {
            $characteristics = [];
            foreach ($product->values as $value) {
                $characteristics[] = new ValueForm($value);
            }
            $this->characteristics = $characteristics;
        } else {
            $this->characteristics = [];
        }

        if ($product->modifications) {
            $modifications = [];
            foreach ($product->modifications as $modification) {
                $modifications[] = new ModificationForm($modification);
            }
            $this->modifications = $modifications;
        } else {
            $this->modifications = [];
        }

        $this->_product = $product;
        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            [['brandId', 'name', 'priceType'], 'required'],
            [['name', 'description', 'priceType'], 'string', 'max' => 255],
            [['brandId', 'quantity'], 'integer'],
        ];
    }

    public function beforeValidate()
    {
        $result = false;
        $isFile = false;
        if (parent::beforeValidate()) {
            if (!empty($data = \Yii::$app->request->bodyParams)) {
            } elseif ($data = UploadedFile::getInstanceByName('data')) {
                $isFile = true;
            }
            $result = $this->loadData($data, $isFile);
        }
        return $result;
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
        return ['meta', 'categories', 'characteristics', 'tags', 'photos', 'price', 'modifications'];
    }

    //-----  loadData ------//

    protected function loadData($data, $file = false)
    {
        $characteristics = 'characteristics';
        $modifications = 'modifications';

        if ($file) {
            $dataFile = $data;
            $data = file_get_contents($dataFile->tempName);
            $data = ArrayHelper::toArray(json_decode($data));
        }

        if ($this::isNotEmptyParams($data[$characteristics])) {
            $this->setForms($characteristics, $data[$characteristics]);
        }

        if ($this::isNotEmptyParams($data[$modifications])) {
            $this->setForms($modifications, $data[$modifications]);
        }

        $this->load($data, '');
        return true;
    }

    protected static function isNotEmptyParams($params)
    {
        return isset($params) && is_array($params);
    }

    protected function setForms($name, $data)
    {
        for ($i = 0; $i < count($data); $i++) {
            $forms[] = $name == 'modifications' ? new ModificationForm() : new ValueForm();
        }
        if ($name == 'modifications') {
            $this->modifications = $forms;
        } else {
            $this->characteristics = $forms;
        }
    }
}