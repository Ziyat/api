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
        '@staticPath' => $params['staticPath'],
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
            ],
        ],

    ],
    'params' => $params,
];
