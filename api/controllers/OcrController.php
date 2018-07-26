<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace api\controllers;


use thiagoalessio\TesseractOCR\Command;
use thiagoalessio\TesseractOCR\TesseractOCR;
use yii\helpers\VarDumper;
use yii\rest\Controller;
use yii\web\UploadedFile;

class OcrController extends Controller
{
    public function actionIndex()
    {
        $image = UploadedFile::getInstanceByName('img');
        $fileName = \Yii::getAlias('@staticPath/ocr/')  . \Yii::$app->security->generateRandomString() .'.'. $image->extension;
        $image->saveAs($fileName);
        $tessaract = new TesseractOCR();
        $tessaract->image($fileName);
        $path = getenv('PATH');
        putenv("PATH=$path:/opt/local/bin");
        putenv("TESSDATA_PREFIX=/opt/local/share/tessdata");
        return $tessaract->run();
    }
}