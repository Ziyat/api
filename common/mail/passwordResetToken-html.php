<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user box\entities\user\User */
/* @var $subject */

?>
<div class="password-reset">
    <p>Hello <?= Html::encode($user->profile->name) ?: Html::encode($user->email) ?>,</p>

    <p><?= Html::encode($subject .': '. $user->password_reset_token) ?></p>
</div>
