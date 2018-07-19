<?php

/* @var $this yii\web\View */
/* @var $user box\entities\user\User */
/* @var $subject */

?>
Hello <?= $user->profile->name ?: $user->email ?>,

<?= $subject ?>: <?= $user->password_reset_token ?>
