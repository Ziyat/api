<?php

namespace box\forms\shop;

use box\entities\shop\Characteristic;
use box\forms\CompositeForm;
use DeepCopy\TypeFilter\Date\DateIntervalFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;

/**
 * @property CharacteristicAssignmentForm[] $assignments
 */
class CharacteristicForm extends CompositeForm
{
    public $name;

    public function __construct(Characteristic $characteristic = null, $config = [])
    {
        if($characteristic){
            $this->name = $characteristic->name;

            if(is_array($assignments = $characteristic->assignments))
            {
                foreach ($assignments as $assignment){
                    $forms[] =  new CharacteristicAssignmentForm($assignment);
                }
                $result = $forms;
            }else{
                $result = null;
            }
            $this->assignments = $result;
        }

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
            $requestAssignments = ArrayHelper::getValue(\Yii::$app->request->bodyParams, 'assignments');
            for ($i = 0; $i < count($requestAssignments); $i++) {
                $forms[$i] = new CharacteristicAssignmentForm();
            }
            $this->assignments =  $forms;

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