<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace api\tests\api\zz;

use api\tests\ApiTester;
class ZClearDataCest
{
    public function clearData(ApiTester $I)
    {
        $I->wantTo('cleaning data base');
        echo PHP_EOL;
        echo PHP_EOL;
        passthru('php yii_test migrate/fresh --interactive=0');
    }
}