<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace box\readModels;

use box\entities\shop\Characteristic;
use box\repositories\NotFoundException;

class CharacteristicReadModel
{
    /**
     * @param $id
     * @return array
     * @throws NotFoundException
     */
    public function findByCategoryId($id): array
    {
        if (!$characteristics = Characteristic::find()->joinWith([
            'assignments' => function ($q) use ($id) {
                $q->where(['category_id' => $id]);
            }
        ])->all()) {
            throw new NotFoundException('Characteristics not found.');
        }
        return $characteristics;
    }

    /**
     * @param $id
     * @return Characteristic
     * @throws NotFoundException
     */
    public function findById($id): Characteristic
    {
        if (!$characteristics = Characteristic::findOne(['id' => $id])) {
            throw new NotFoundException('Characteristics not found.');
        }

        return $characteristics;
    }
}