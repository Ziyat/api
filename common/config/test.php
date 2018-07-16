<?php
return [
    'id' => 'app-common-tests',
    'basePath' => dirname(__DIR__),
    'components' => [
        'user' => [
            'class' => 'yii\web\User',
            'identityClass' => 'box\entities\user\User',
        ],
        'mailer' => [
            'useFileTransport' => true,
        ]
    ],
];
