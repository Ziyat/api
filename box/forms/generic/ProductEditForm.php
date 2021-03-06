<?php

namespace box\forms\generic;

use box\entities\generic\GenericProduct;
use box\entities\generic\GenericRating;
use box\entities\shop\Brand;
use box\forms\CompositeForm;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;

/**
 * @property CategoriesForm $categories
 * @property ValueForm[] $characteristics
 * @property TagsForm $tags
 * @property ModificationForm[] $modifications
 * @property GenericRating[] $ratings
 */
class ProductEditForm extends CompositeForm
{
    public $brandId;
    public $name;
    public $description;
    public $priceType;
    public $quantity;

    private $_product;

    public function __construct(GenericProduct $product, $config = [])
    {
        $this->brandId = $product->brand_id;
        $this->name = $product->name;
        $this->description = $product->description;
        $this->categories = new CategoriesForm($product);
        $this->tags = new TagsForm($product);
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
//            [['brandId', 'name'], 'required'],
            [['name', 'description'], 'string', 'max' => 255],
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
        return ['categories', 'characteristics', 'tags', 'modifications'];
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

        if (!empty($data[$characteristics]) && $this::isNotEmptyParams($data[$characteristics])) {
            $this->setForms($characteristics, $data[$characteristics]);
        }

        if (!empty($data[$modifications]) && $this::isNotEmptyParams($data[$modifications])) {
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