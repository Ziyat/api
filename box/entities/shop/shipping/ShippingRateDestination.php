<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace box\entities\shop\shipping;

use yii\db\ActiveRecord;

/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 * Class ShippingRateDestination
 * @package box\entities\shop\shipping
 *
 * @property integer $destination_id
 * @property integer $rate_id
 */
class ShippingRateDestination extends ActiveRecord
{
    public static function create($destination_id)
    {
        $destination = new static();
        $destination->destination_id = $destination_id;
        return $destination;
    }

    public function isIdEqualTo($id)
    {
        return $this->destination_id == $id;
    }

    public static function tableName()
    {
        return '{{%shipping_rate_destinations}}';
    }
}