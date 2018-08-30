<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace box\forms\shop;

use box\entities\shop\CharacteristicAssignment;
use yii\base\Model;

/**
 * Class CharacteristicAssignmentsForm
 * @package box\forms\shop
 */
class CharacteristicAssignmentForm extends Model
{
    public $category_id;
    public $characteristic_id;
    public $variants = [];

    public function __construct(CharacteristicAssignment $characteristicAssignment = null, array $config = [])
    {
        if($characteristicAssignment)
        {
            $this->characteristic_id = $characteristicAssignment->characteristic_id;
            $this->category_id = $characteristicAssignment->category_id;
            $this->variants = $characteristicAssignment->variants;
        }
        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            ['category_id', 'required'],
            ['category_id', 'integer'],
            [['variants','characteristic_id'], 'safe'],
        ];
    }
}