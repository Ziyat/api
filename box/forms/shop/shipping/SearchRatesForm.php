<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace box\forms\shop\shipping;


use yii\base\Model;

class SearchRatesForm extends Model
{
    public $weight;
    public $destinations;

    public function rules()
    {
        return [
            ['weight', 'double'],
            ['destinations', 'safe'],
        ];
    }
}