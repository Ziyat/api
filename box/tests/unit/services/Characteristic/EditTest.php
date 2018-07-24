<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace box\tests\unit\entities\Characteristic;

use box\entities\shop\Characteristic;
use box\forms\shop\CharacteristicForm;
use box\repositories\CategoryRepository;
use box\repositories\CharacteristicRepository;
use box\services\CharacteristicService;
use Codeception\Test\Unit;
use common\fixtures\shop\CharacteristicFixture;
use yii\helpers\VarDumper;

class CharacteristicServiceEditTest extends Unit
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
        $this->service = new CharacteristicService(new CharacteristicRepository(),new CategoryRepository());
    }
    public function testSuccess()
    {

//        $characteristic = Characteristic::findOne(1);
//
//        $form = new CharacteristicForm($characteristic);
//
//        $form->name = 'glass2';
//
//        $form->textVariants = 'sapphire'.PHP_EOL.'crystals'.PHP_EOL.'mineral crystals';
//
//        $this->service->edit($characteristic->id,$form);
//
//        $find = Characteristic::findOne($characteristic->id);
//
//        $this->assertEquals($find->name, 'glass2');
//
//        $this->assertTrue($find->isSelect());

    }
}