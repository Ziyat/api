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
    public $curPrice;
    public $deadline;

    public function __construct(Product $product = null, array $config = [])
    {
        if($product){
            $this->curPrice = $product->price->cur_price;
            $this->deadline = $product->price->deadline;
        }

        parent::__construct($config);

    }

    public function rules()
    {
        return array_filter([
            ['curPrice', 'required'],
            ['curPrice', 'number'],
            $this->deadline ? ['deadline', 'integer'] : false
        ]);
    }
}