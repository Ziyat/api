<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace console\controllers;

use yii\console\Controller;
use Yii;

class DocController extends Controller
{
    public function actionBuild(): void
    {
        $swagger = Yii::getAlias('@vendor/bin/swagger');
        $source = Yii::getAlias('@api/controllers');
        $target = Yii::getAlias('@api/web/docs/swagger.json');

        passthru('"'. PHP_BINARY . '"' . " \"{$swagger}\" \"{$source}\" --output \"{$target}\"");
    }
}