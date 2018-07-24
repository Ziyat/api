<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace box\forms\shop;

use yii\base\Model;

/**
 * Class CharacteristicAssignmentsForm
 * @package box\forms\shop
 */
class CharacteristicAssignmentForm extends Model
{
    public $category_id;
    public $variants = [];

    public function rules(): array
    {
        return [
            ['category_id', 'required'],
            ['category_id', 'integer'],
            ['variants', 'safe'],
        ];
    }
}