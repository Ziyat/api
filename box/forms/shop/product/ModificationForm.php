<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace box\forms\shop\product;


use box\entities\shop\Characteristic;
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

    public function rules()
    {
        return [
            [['characteristic_id','value'],'required'],
            ['value','trim'],
            ['value','string'],
            [['price','main_photo_id','quantity'],'integer'],
            ['characteristic_id', 'exist',
                'targetClass' => Characteristic::class,
                'message' => 'There is no characteristic with this id.'
            ]
        ];
    }
}