<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace api\controllers\shop;

use api\controllers\BearerCrudController;

class ProductController extends BearerCrudController
{
    public function actionIndex()
    {
        return 'Hello';
    }
}