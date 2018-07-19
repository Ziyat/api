<?php

namespace box\forms\shop\product;

use box\entities\shop\characteristic;
use box\entities\shop\product\Value;
use function Faker\Provider\pt_BR\check_digit;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;

/**
 * @property string $value
 * @property integer $id
 */
class ValueForm extends Model
{
    public $value;
    public $id;

    private $_oldValue;

    public function __construct(Value $value= null, $config = [])
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