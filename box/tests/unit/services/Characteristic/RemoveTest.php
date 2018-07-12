<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace box\tests\unit\services\Characteristic;

use box\entities\shop\Characteristic;
use box\forms\shop\CharacteristicForm;
use box\repositories\CharacteristicRepository;
use box\services\CharacteristicService;
use Codeception\Test\Unit;
use common\fixtures\shop\CharacteristicFixture;

class CharacteristicServiceRemoveTest extends Unit
{
    public function _fixtures()
    {
        return [
            'characteristics' => [
                'class' => CharacteristicFixture::class,
                'dataFile' => codecept_data_dir() . 'characteristic.php'
            ]
        ];
    }
    public $service;

    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->service = new CharacteristicService(new CharacteristicRepository());
    }

    public function testSuccess()
    {

        $characteristic = Characteristic::findOne(1);

        $this->service->remove($characteristic->id);

        $this->assertNull(Characteristic::findOne($characteristic->id));
    }
}