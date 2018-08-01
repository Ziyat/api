<?php

namespace box\forms\generic;

use box\entities\shop\Brand;
use box\forms\CompositeForm;
use box\forms\manage\MetaForm;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;

/**
 * @property CategoriesForm $categories
 * @property ValueForm[] $characteristics
 * @property TagsForm $tags
 * @property PhotosForm $photos
 * @property ModificationForm[] $modifications
 * @property integer $brandId
 * @property string $name
 * @property string $description
 */
class ProductCreateForm extends CompositeForm
{
    public $brandId;
    public $name;
    public $description;
    public $characteristics = [];
    public $modifications = [];
    public $internalForms = ['categories', 'tags', 'photos'];

    public function __construct($config = [])
    {
        $this->categories = new CategoriesForm();
        $this->tags = new TagsForm();
        $this->photos = new PhotosForm();
        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            [['brandId', 'name'], 'required'],
            [['name', 'description'], 'string', 'max' => 255],
            [['brandId'], 'integer'],
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
            $this->internalForms[] = $characteristics;
        }

        if (!empty($data[$modifications]) && $this::isNotEmptyParams($data[$modifications])) {
            $this->setForms($modifications, $data[$modifications]);
            $this->internalForms[] = $modifications;
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