<?php
return [
    'name' => 'Watch Valt',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
        '@staticPath' => $params['staticPath'],
        '@staticUrl'   => $params['staticHostInfo'],
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
    ],
];
