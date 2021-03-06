<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace api\controllers;

use box\entities\Meta;
use box\entities\shop\Brand;
use yii\helpers\VarDumper;
use yii\rest\Controller;

/**
 * @SWG\Swagger(
 *     basePath="/",
 *     host="api.watchvaultapp.com",
 *     schemes={"http"},
 *     produces={"application/json","application/xml"},
 *     consumes={"application/json","application/xml","application/x-www-form-urlencoded"},
 *     @SWG\SecurityScheme(
 *         securityDefinition="Bearer",
 *         type="apiKey",
 *         name="Authorization",
 *         in="header"
 *     ),
 *     @SWG\Info(
 *         version="1.0.0",
 *         title="Watch Vault apps API",
 *         description="HTTP JSON API",
 *     )
 * )
 */


class SiteController extends Controller
{
    /**
     * @SWG\Get(
     *     path="/",
     *     tags={"Info"},
     *     @SWG\Response(
     *         response="200",
     *         description="API version",
     *         @SWG\Schema(
     *             type="object",
     *             @SWG\Property(property="version", type="string")
     *         ),
     *     )
     * )
     */
    public function actionIndex()
    {
        return [
            'version' => '1.0.0',
        ];
    }
}
