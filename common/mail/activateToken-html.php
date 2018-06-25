<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user box\entities\User */
?>
<div class="password-reset">
    <p>Activate Code: <?= Html::encode($user->activate_token) ?></p>
</div>
