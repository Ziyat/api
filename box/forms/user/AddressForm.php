<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace box\forms\user;

use box\entities\user\Address;
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

    public function __construct(Address $address = null, array $config = [])
    {
        if ($address) {
            $this->country_id = $address->country_id;
            $this->name = $address->name;
            $this->phone = $address->phone;
            $this->address_line_1 = $address->address_line_1;
            $this->address_line_2 = $address->address_line_2;
            $this->city = $address->city;
            $this->state = $address->state;
            $this->index = $address->index;
            $this->default = $address->default;
        }
        parent::__construct($config);
    }

    public function rules()
    {
        return [
            [['country_id', 'name','address_line_1','city','index'], 'required'],
            [['name','address_line_1','address_line_2','city','index','phone','state'], 'string'],
            [['country_id','default'], 'integer'],
        ];
    }


}