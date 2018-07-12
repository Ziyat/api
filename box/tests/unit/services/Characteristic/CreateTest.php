<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace box\tests\unit\entities\Characteristic;

use box\entities\Meta;
use box\entities\shop\Brand;
use box\entities\shop\Characteristic;
use box\forms\shop\CharacteristicForm;
use box\repositories\CharacteristicRepository;
use box\services\CharacteristicService;
use Codeception\Test\Unit;

class CharacteristicServiceCreateTest extends Unit
{
    public $service;

    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->service = new CharacteristicService(new CharacteristicRepository());
    }

    public function testSuccess()
    {
        $name = 'Name';
        $type = Characteristic::TYPE_INTEGER;
        $required = true;
        $variants = 'sapphire' . PHP_EOL . 'crystals' . PHP_EOL . 'mineral crystals';
        $sort = 15;

        $form = new CharacteristicForm();
        $form->name = $name;
        $form->type = $type;
        $form->required = $required;
        $form->textVariants = $variants;
        $form->sort = $sort;

        $characteristic = $this->service->create($form);

        $this->assertTrue($characteristic->isSelect());
        $this->assertEquals($characteristic->name, $name);

    }
}