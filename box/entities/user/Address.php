<?php

namespace box\entities\user;

use box\entities\Country;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "profiles".
 *
 * @property int $id
 * @property int $user_id
 * @property int $country_id
 * @property string $name
 * @property string $phone
 * @property string $address_line_1
 * @property string $address_line_2
 * @property string $city
 * @property string $state
 * @property string $index
 * @property boolean $default
 *
 * @property User $user
 * @property Country $country
 */
class Address extends ActiveRecord
{
    const NON_DEFAULT = 0;
    const DEFAULT = 1;

    public static function create(
        $name,
        $phone,
        $country_id,
        $address_line_1,
        $address_line_2,
        $city,
        $state,
        $index,
        $default
    )
    {
        $address = new static();
        $address->name = $name;
        $address->phone = $phone;
        $address->country_id = $country_id;
        $address->address_line_1 = $address_line_1;
        $address->address_line_2 = $address_line_2;
        $address->city = $city;
        $address->state = $state;
        $address->index = $index;
        $address->default = $default;

        return $address;
    }

    public function edit(
        $name,
        $phone,
        $country_id,
        $address_line_1,
        $address_line_2,
        $city,
        $state,
        $index,
        $default)
    {
        $this->name = $name;
        $this->phone = $phone;
        $this->country_id = $country_id;
        $this->address_line_1 = $address_line_1;
        $this->address_line_2 = $address_line_2;
        $this->city = $city;
        $this->state = $state;
        $this->index = $index;
        $this->default = $default;
    }

    public function nonDefault()
    {
        $this->default = self::NON_DEFAULT;
    }

    public function isDefault()
    {
        return $this->default == self::DEFAULT;
    }

    public function setDefault()
    {
        $this->default = self::DEFAULT;
    }

    public function changeDefault()
    {
       if($this->isDefault()){
           $this->nonDefault();
       }else{
           $this->setDefault();
       }
    }




    public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public function getCountry(): ActiveQuery
    {
        return $this->hasOne(Country::class, ['id' => 'country_id']);
    }


    public static function tableName()
    {
        return '{{%user_addresses}}';
    }

    public function fields()
    {
        return [
            'id' => 'id',
            'name' => 'name',
            'country' => function (self $model) {
                return [
                    'id' => $model->country->id,
                    'name' => $model->country->name,
                    'code' => $model->country->code,
                ];
            },
            'phone' => 'phone',
            'address_line_1' => 'address_line_1',
            'address_line_2' => 'address_line_2',
            'city' => 'city',
            'state' => 'state',
            'index' => 'index',
            'default' => 'default'
        ];
    }

}
