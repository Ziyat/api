<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace box\forms\publicForms;

use yii\base\Model;

class UserProductShippingForm extends Model
{
    public $user_id;
    public $free;
    public $pickup;

    public function rules()
    {
        return [
            [['user_id','free','pickup'], 'integer']
        ];
    }
}