<?php

namespace box\forms\shop\product;

use box\entities\shop\Brand;
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
 * @property integer $brandId
 * @property integer $quantity
 * @property string $name
 * @property string $description
 * @property string $priceType
 * @property string $condition
 */
class ProductCreateForm extends CompositeForm
{
    public $brandId;
    public $name;
    public $condition;
    public $description;
    public $priceType;
    public $quantity;
    public $internalForms = ['meta', 'categories', 'tags', 'photos', 'price','characteristics', 'modifications'];

    public function __construct($config = [])
    {
        $this->meta = new MetaForm();
        $this->categories = new CategoriesForm();
        $this->tags = new TagsForm();
        $this->photos = new PhotosForm();
        $this->price = new PriceForm();
        $this->characteristics = new ValueForm();
        $this->modifications = new ModificationForm();
        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            [['brandId', 'name', 'priceType'], 'required'],
            [['name', 'description', 'priceType','condition'], 'string', 'max' => 255],
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
        return $this->internalForms;
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
        }else{
            unset($this->internalForms[$characteristics]);
            $this->characteristics = [];
        }

        if (!empty($data[$modifications]) && $this::isNotEmptyParams($data[$modifications])) {
            $this->setForms($modifications, $data[$modifications]);
        }else{
            unset($this->internalForms[$modifications]);
            $this->modifications = [];
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