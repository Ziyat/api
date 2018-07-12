<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-api',
    'basePath' => dirname(__DIR__),
    'aliases' => [
        '@staticUrl' => $params['staticHostInfo'],
    ],
    'controllerNamespace' => 'api\controllers',
    'bootstrap' => [
        'log',
        [
            'class' => 'yii\filters\ContentNegotiator',
            'formats' => [
                'application/json' => 'json',
            ],
        ],
    ],
    'components' => [
        'request' => [
            "csrfCookie" => [
                "httpOnly" => false
            ],
            'enableCookieValidation' => false,
            'enableCsrfValidation' => false,
            "enableCsrfCookie" => false,
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ],
        ],
        'response' => [
            'formatters' => [
                'json' => [
                    'class' => 'yii\web\JsonResponseFormatter',
                    'prettyPrint' => YII_DEBUG,
                    'encodeOptions' => JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE,
                ],
            ],
        ],
        'user' => [
            'identityClass' => 'box\entities\user\User',
            'enableAutoLogin' => false,
            'enableSession' => false,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'enableStrictParsing' => true,
            'showScriptName' => false,
            'rules' => [
                'GET,HEAD /' => 'site/index',
                'login' => 'auth/login',
                'signup' => 'auth/signup',
                'activate/<token:[\d_]+>' => 'auth/activate-user',
                'profile' => 'user/profile/index',
                'profile/edit' => 'user/profile/edit',

                'GET user/products' => 'user/product/index',
                'POST user/products' => 'user/product/create',
                'PUT user/products/<id:\d+>' => 'user/product/edit',


                'GET user/brand-list' => 'user/product/brands-list',



                'GET shop/products/<id:\d+>' => 'shop/product/view',
                'GET shop/products/category/<id:\d+>' => 'shop/product/category',
                'GET shop/products/brand/<id:\d+>' => 'shop/product/brand',
                'GET shop/products/tag/<id:\d+>' => 'shop/product/tag',
                'GET shop/products' => 'shop/product/index',
            ],
        ],

    ],
    'params' => $params,
];
