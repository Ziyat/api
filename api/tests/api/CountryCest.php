<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace api;


use api\tests\ApiTester;

class CountryCest
{

    public function allCountries(ApiTester $I)
    {
        $I->sendGET('/public/countries');
        $I->seeResponseContainsJson();

    }

    public function CountryById(ApiTester $I)
    {
        $I->sendGET('/public/countries/224');
        $I->seeResponseContainsJson([
            'id' => 224,
            'name' => 'Yemen',
            'code' => 'ye'
        ]);

    }

    public function CountryByCode(ApiTester $I)
    {
        $I->sendGET('/public/countries/ye');
        $I->seeResponseContainsJson([
            'id' => 224,
            'name' => 'Yemen',
            'code' => 'ye'
        ]);

    }
}