<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace box\entities\shop\product;

use box\entities\behaviors\CountryBehavior;
use yii\db\ActiveRecord;

/**
 * Class ShippingAssignment
 * @package box\entities\shop\product
 * @property integer $product_id
 * @property integer $rate_id
 * @property integer $free_shipping_type
 * @property float $price
 * @property string $countries
 * @property array $countriesIds
 */
class ShippingAssignment extends ActiveRecord
{
    const TYPE_NO_FREE = 0;
    const TYPE_FREE = 1;
    const TYPE_PICKUP = 2;

    public $countriesIds;

    public static function create(
        $rate_id,
        $countriesIds,
        $free_shipping_type,
        $price
    ): self
    {
        $assignment = new static();
        $assignment->rate_id = $rate_id;
        $assignment->countriesIds = $countriesIds;
        $assignment->free_shipping_type = $free_shipping_type;
        $assignment->price = $price;
        return $assignment;
    }

    public function isForRateId($id): bool
    {
        return $this->rate_id == $id;
    }

    public function behaviors()
    {
        return [
            CountryBehavior::class,
        ];
    }

    public static function tableName(): string
    {
        return '{{%shipping_assignments}}';
    }
}