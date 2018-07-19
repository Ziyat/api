<?php
namespace box\forms\auth;

use yii\base\InvalidArgumentException;
use yii\base\Model;
use common\models\User;

/**
 * Password reset form
 */
class SetPasswordForm extends Model
{
    public $password;

    public function rules()
    {
        return [
            ['password', 'required'],
            ['password', 'string', 'min' => 6],
        ];
    }

}
