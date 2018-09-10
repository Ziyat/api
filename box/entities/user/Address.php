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
    const NOT_DEFAULT = 0;
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
//
//    public function edit($name,$lastName,$birthDate,$photo)
//    {
//        $this->name = $name;
//        $this->photo = $photo;
//        $this->last_name = $lastName;
//        $this->date_of_birth = strtotime($birthDate);
//    }

    public function doNotDefault()
    {
        $this->default = self::NOT_DEFAULT;
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
                    'name' => $model->country->name
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