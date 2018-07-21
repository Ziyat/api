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
 * @property float $current
 * @property float $deadline
 * @property float $max
 * @property float $end
 * @property float $buyNow
 */

class PriceForm extends Model
{
    public $current;
    public $deadline;
    public $max;
    public $end;
    public $buyNow;

    private $_product;

    public function __construct(Product $product = null, array $config = [])
    {
        if($product){
            $this->current = $product->price->current;

            if($product->isBargainPrice())
            {
                $this->max = $product->price->max;
                $this->end = $product->price->end;
            }

            $this->_product = $product;
        }

        parent::__construct($config);

    }

    public function rules()
    {
        return array_filter([
            ['current', 'required'],
            [['current','max','end','buyNow'], 'number']
        ]);
    }
}