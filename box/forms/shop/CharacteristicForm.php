<?php

namespace box\forms\shop;

use box\forms\CompositeForm;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;

/**
 * @property CharacteristicAssignmentForm[] $assignments
 */
class CharacteristicForm extends CompositeForm
{
    public $name;

    public function __construct($config = [])
    {
        $this->assignments = [];
        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            [['name'], 'required'],
        ];
    }

    public function beforeValidate()
    {
        if (parent::beforeValidate()) {
            $forms = [];
            $assignments = ArrayHelper::getValue(\Yii::$app->request->bodyParams, 'assignments');
            for ($i = 0; $i < count($assignments); $i++) {
                $forms[$i] = new CharacteristicAssignmentForm();
            }
            $this->assignments = $forms;
            $this->load(\Yii::$app->request->bodyParams,'');
            return true;
        }

        return false;
    }

    public function internalForms(): array
    {
        return ['assignments'];
    }
}