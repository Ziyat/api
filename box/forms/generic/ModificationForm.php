<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace box\forms\generic;

use box\entities\shop\Characteristic;
use box\entities\generic\GenericModification;
use yii\base\Model;

/**
 * @property string $value
 * @property integer $characteristic_id
 * @property integer $main_photo_id
 */

class ModificationForm extends Model
{
    public $value;
    public $characteristic_id;
    public $main_photo_id;

    private $_modification;

    public function __construct(GenericModification $modification = null, $config = [])
    {
        if($modification){
            $this->value = $modification->value;
            $this->characteristic_id = $modification->characteristic_id;
            $this->main_photo_id = $modification->main_photo_id;
            $this->_modification = $modification;
        }


        parent::__construct($config);
    }

    public function rules()
    {
        return [
            [['characteristic_id','value'],'required'],
            ['value','trim'],
            ['value','string'],
            [['main_photo_id'],'integer'],
            ['characteristic_id', 'exist',
                'targetAttribute' => 'id',
                'targetClass' => Characteristic::class,
                'message' => 'There is no characteristic with this id.'
            ]
        ];
    }
}