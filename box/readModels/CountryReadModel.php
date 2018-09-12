<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace box\readModels;

use box\entities\Country;

class CountryReadModel
{
    public function getCountries()
    {
        return Country::find()->all();
    }

    public function getCountry($id)
    {
        return Country::findOne($id);
    }

    public function getCountryByCode($code)
    {
        return Country::findOne(['code' => $code]);
    }
}