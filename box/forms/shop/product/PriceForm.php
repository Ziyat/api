<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace box\forms\shop\product;

use box\entities\shop\product\Price;
use box\entities\shop\product\Product;
use yii\base\Model;


/**
 * @property integer $id
 * @property float $curPrice
 * @property float $deadline
 */

class PriceForm extends Model
{
    public $current;
    public $deadline;
    public $max;
    public $end;
    public $buyNow;

    public function __construct(Product $product = null, array $config = [])
    {
        if($product){
            $this->current = $product->price->current;
            $this->deadline = $product->price->deadline;
        }

        parent::__construct($config);

    }

    public function rules()
    {
        return array_filter([
            ['current', 'required'],
            [['current','max','end','buyNow'], 'number'],
            $this->deadline ? ['deadline', 'integer'] : false
        ]);
    }
}