<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace box\forms\generic;

use box\entities\generic\GenericRating;
use yii\base\Model;

/**
 * @property $names
 */
class RatingsForm extends Model
{
    public $names;

    public function rules()
    {
        return [
            ['names' , 'each', 'rule' => ['string']]
        ];
    }
}