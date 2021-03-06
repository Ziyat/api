<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace box\forms\carousel;


use yii\base\Model;
use yii\web\UploadedFile;

/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 * Class ImageForm
 * @package box\forms\carousel
 * @property array $files
 */

class ImageForm extends Model
{
    public $files;

    public function rules(): array
    {
        return [
            ['files', 'each', 'rule' => ['image']],
        ];
    }

    public function beforeValidate(): bool
    {
        if (parent::beforeValidate()) {
            $this->files = UploadedFile::getInstancesByName('files');
            return true;
        }
        return false;
    }
}