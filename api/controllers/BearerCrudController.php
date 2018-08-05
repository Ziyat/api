<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace api\controllers;


use yii\filters\AccessControl;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\rest\Controller;

class BearerCrudController extends Controller
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['authenticator']['authMethods'] = [
            HttpBasicAuth::class,
            HttpBearerAuth::class,
        ];
        $behaviors['authenticator']['only'] = ['create', 'update', 'delete'];

        $behaviors['access'] = [
            'class' => AccessControl::class,
            'only' => ['create', 'update', 'delete'],
            'rules' => [
                [
                    'allow' => true,
                    'roles' => ['@']
                ],
            ],
        ];


        return $behaviors;
    }
}
/**
<VirtualHost *:80>
ServerName test.watchvaultapp.com
        DocumentRoot "/var/www/api.watchvaultapp.com/api/web/"
<Directory "/var/www/api.watchvaultapp.com/api/web/">
# Always set these headers.
Header always set Access-Control-Allow-Origin "*"
                Header always set Access-Control-Allow-Methods "POST, GET, OPTIONS, DELETE, PUT, PATCH"
                Header always set Access-Control-Max-Age "1000"
                Header always set Access-Control-Allow-Headers "x-requested-with, Content-Type, origin, aut$
                # every OPTIONS request.
                RewriteEngine On
                RewriteCond %{REQUEST_METHOD} OPTIONS
                RewriteRule ^(.*)$ $1 [R=200,L]

                RewriteCond %{REQUEST_FILENAME} !-f
                RewriteCond %{REQUEST_FILENAME} !-d
                RewriteRule . index-test.php
                DirectoryIndex index-test.php
                Require all granted
        </Directory>
        ErrorLog /var/www/apache2/log/api.watchvaultapp.com-error.log
        CustomLog /var/www/apache2/log/api.watchvaultapp.com-access.log combined
</VirtualHost>

**/