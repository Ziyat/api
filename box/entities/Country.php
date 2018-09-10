<?php

namespace box\entities;

use yii\db\ActiveRecord;
/**
 *
 * @property int $id
 * @property string $name
 * @property string $code
 */
class Country extends ActiveRecord
{

    public static function tableName()
    {
        return '{{%countries}}';
    }

}
