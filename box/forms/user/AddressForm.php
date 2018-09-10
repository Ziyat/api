<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace box\forms\user;

use yii\base\Model;

class AddressForm extends Model
{
    public $country_id;
    public $name;
    public $phone;
    public $address_line_1;
    public $address_line_2;
    public $city;
    public $state;
    public $index;
    public $default = 0;

    public function rules()
    {
        return [
            [['country_id', 'name','address_line_1','city','index'], 'required'],
            [['name','address_line_1','address_line_2','city','index','phone','state'], 'string'],
            [['country_id','default'], 'integer'],
        ];
    }


}