<?php

namespace box\forms\Shop;

use box\entities\Shop\Brand;
use box\forms\CompositeForm;
use box\forms\manage\MetaForm;
use box\validators\SlugValidator;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;

/**
 * @property MetaForm $meta;
 * @property $name;
 * @property $slug;
 * @property $photo;
 */
class BrandForm extends CompositeForm
{
    public $name;
    public $slug;
    public $photo;

    private $_brand;

    public function __construct(Brand $brand = null, $config = [])
    {
        if ($brand) {
            $this->name = $brand->name;
            $this->slug = $brand->slug;
            $this->photo = $brand->photo;
            $this->meta = new MetaForm($brand->meta);
            $this->_brand = $brand;
        } else {
            $this->meta = new MetaForm();
        }
        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            [['name', 'slug'], 'required'],
            [['name', 'slug'], 'string', 'max' => 255],
            ['slug', SlugValidator::class],
            [
                ['name', 'slug'],
                'unique',
                'targetClass' => Brand::class,
                'filter' => $this->_brand ? ['<>', 'id', $this->_brand->id] : null
            ],
            ['photo', 'file', 'extensions' => 'jpeg, gif, png, jpg'],
        ];
    }

    public function beforeValidate(): bool
    {
        if (parent::beforeValidate()) {
            $this->photo = UploadedFile::getInstanceByName('photo');
            if($dataFile = UploadedFile::getInstanceByName('data')){
                $data = file_get_contents($dataFile->tempName);
                $data = ArrayHelper::toArray(json_decode($data));
                $this->load($data,'');
            }
            return true;
        }
        return false;
    }



    protected function UploadedData($name)
    {

        if ($file = UploadedFile::getInstanceByName($name)) {
            $data = file_get_contents($file->tempName);
            return json_decode($data);
        }
        return false;
    }

    public function internalForms(): array
    {
        return ['meta'];
    }


}