<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace controllers\shop;


use box\entities\shop\Brand;
use yii\rest\Controller;

class BrandController extends Controller
{
    public function actionIndex()
    {
        return Brand::find()->all();
    }
}