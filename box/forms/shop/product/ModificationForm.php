<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace box\forms\shop\product;


use box\entities\shop\Characteristic;
use box\entities\shop\product\Modification;
use yii\base\Model;

/**
 * @property string $value
 * @property integer $price
 * @property integer $characteristic_id
 * @property integer $main_photo_id
 * @property int $quantity
 */

class ModificationForm extends Model
{
    public $value;
    public $price;
    public $characteristic_id;
    public $main_photo_id;
    public $quantity;

    private $_modification;

    public function __construct(Modification $modification= null, $config = [])
    {
        if($modification){
            $this->value = $modification->value;
            $this->characteristic_id = $modification->characteristic_id;
            $this->price = $modification->price;
            $this->main_photo_id = $modification->main_photo_id;
            $this->quantity = $modification->quantity;
            $this->_modification = $modification;
        }


        parent::__construct($config);
    }

    public function rules()
    {
        return [
            [['characteristic_id','value','quantity','price'],'required'],
            ['value','trim'],
            ['value','string'],
            [['price','main_photo_id','quantity'],'integer'],
            ['characteristic_id', 'exist',
                'targetAttribute' => 'id',
                'targetClass' => Characteristic::class,
                'message' => 'There is no characteristic with this id.'
            ]
        ];
    }
}