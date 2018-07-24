<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace box\tests\unit\entities\Characteristic;

use box\entities\Meta;
use box\entities\shop\Brand;
use box\entities\shop\Characteristic;
use box\forms\shop\CharacteristicAssignmentForm;
use box\forms\shop\CharacteristicForm;
use box\repositories\CategoryRepository;
use box\repositories\CharacteristicRepository;
use box\services\CharacteristicService;
use Codeception\Test\Unit;
use common\fixtures\shop\CategoryFixture;
use yii\helpers\VarDumper;

class CharacteristicServiceCreateTest extends Unit
{
    public function _fixtures()
    {
        return [
            'categories' => [
                'class' => CategoryFixture::class,
                'dataFile' => codecept_data_dir() . 'category.php'
            ]
        ];
    }
    public $service;

    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->service = new CharacteristicService(new CharacteristicRepository(), new CategoryRepository());
    }

    public function testSuccess()
    {
        $name = 'Name';

        $form = new CharacteristicForm();
        $form->name = $name;
        $assignForm = new CharacteristicAssignmentForm();
        $assignForm->category_id = 2;
        $assignForm->variants = ['dasdas','dasdsad'];
        $assignmets[] = $assignForm;
        $form->assignments = $assignmets;
        $characteristic = $this->service->create($form);

        $this->assertEquals($characteristic->name, $name);
        $this->assertEquals($characteristic->assignments[0]->variants, ['dasdas','dasdsad']);
        $this->assertEquals($characteristic->categories[0]->name, 'Notebook');


    }
}