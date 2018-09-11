<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace box\repositories;


use box\entities\Country;

class CountryRepository
{
    public function get($id): Country
    {
        if (!$country = Country::findOne($id)) {
            throw new NotFoundException('Country is not found.');
        }
        return $country;
    }

    /**
     * @return array|\yii\db\ActiveRecord[]
     * @throws NotFoundException
     */
    public function getAll()
    {
        if (!$countries = Country::find()->all()) {
            throw new NotFoundException('Country is not found.');
        }
        return $countries;
    }
}