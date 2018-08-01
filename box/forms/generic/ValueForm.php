<?php

namespace box\forms\generic;

use box\entities\generic\GenericValue;
use yii\base\Model;

/**
 * @property string $value
 * @property integer $id
 */
class ValueForm extends Model
{
    public $value;
    public $id;

    private $_oldValue;

    public function __construct(GenericValue $value= null, $config = [])
    {
        if($value){
            $this->value = $value->value;
            $this->id = $value->characteristic_id;
            $this->_oldValue = $value;
        }


        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            ['value', 'string'],
            ['id', 'integer'],
        ];
    }
}