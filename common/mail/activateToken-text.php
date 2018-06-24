<?php

/* @var $this yii\web\View */
/* @var $user box\entities\User */
?>
Activate Code: <?= explode('_',$user->activate_token)[0] ?>
