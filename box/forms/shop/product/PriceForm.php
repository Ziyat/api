<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace box\forms\shop\product;

use yii\base\Model;


/**
 * @property integer $id
 * @property float $curPrice
 * @property float $deadline
 */

class PriceForm extends Model
{
    public $curPrice;
    public $deadline;

    public function rules()
    {
        return array_filter([
            ['curPrice', 'required'],
            ['curPrice', 'number'],
            $this->deadline ? ['deadline', 'integer'] : false
        ]);
    }
}