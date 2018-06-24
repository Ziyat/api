<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user box\entities\User */
?>
<div class="password-reset">
    <p>Activate Code: <?= Html::encode(explode('_',$user->activate_token)[0]) ?></p>
</div>
