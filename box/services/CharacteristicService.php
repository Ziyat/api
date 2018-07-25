<?php

namespace box\services;

use box\entities\shop\Characteristic;
use box\entities\shop\CharacteristicAssignment;
use box\forms\shop\CharacteristicForm;
use box\repositories\CategoryRepository;
use box\repositories\CharacteristicRepository;
use box\repositories\NotFoundException;
use yii\helpers\VarDumper;

class CharacteristicService
{
    private $characteristics;
    private $categories;

    public function __construct(
        CharacteristicRepository $characteristics,
        CategoryRepository $categories
    )
    {
        $this->characteristics = $characteristics;
        $this->categories = $categories;
    }

    public function create(CharacteristicForm $form): Characteristic
    {

        try{
            $characteristic =  $this->characteristics->findByName($form->name);
        }catch (NotFoundException $e){
            $characteristic = Characteristic::create($form->name);
        }

        foreach ($form->assignments as $assignment){
            $category = $this->categories->get($assignment->category_id);
            $characteristic->assignCategory($category->id, $assignment->variants);
        }

        $this->characteristics->save($characteristic);
        return $characteristic;
    }

    public function edit($id, CharacteristicForm $form): void
    {
        $characteristic = $this->characteristics->get($id);
        $characteristic->edit(
            $form->name
        );
        $this->characteristics->save($characteristic);
    }

    /**
     * @param $id
     * @throws NotFoundException
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function remove($id): void
    {
        $characteristic = $this->characteristics->get($id);
        foreach ($characteristic->assignments as $assignment){
            $assignment->delete();
        }

        $this->characteristics->remove($characteristic);
    }
}