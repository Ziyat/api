<?php
return [
    'name' => 'Watch Vault',
    'bootstrap' => [
        'log',
        'common\bootstrap\SetUp',
    ],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'fireBase' => [
            'class' => \understeam\fcm\Client::class,
            'apiKey' => 'AAAAN6z8OAc:APA91bHe0x2uqyNRlRWX69HSLYbnBfR8XaUAraUcoCsOaM9Ccq8SHlG4kvaHJzZbXIoNi_ZPgJCGTtmsQ2fAPeMFyl_9ql41072JdF4uHt8OQpH415DDxadZYaAsg82PUGwPTmROL5eZ',
        ],
        'emailService' => [
            'class' => \box\components\EmailService::class
        ],
        'notificationComponent' => [
            'class' => box\components\NotificationComponent::class
        ],
        'cache' => [
            'class' => \yii\caching\FileCache::class,
        ],
        'authManager' => [
            'class' => 'common\components\AuthManager',
            'itemFile' => '@common/components/rbac/items.php',
            'assignmentFile' => '@common/components/rbac/assignments.php',
            'ruleFile' => '@common/components/rbac/rules.php'
        ],

    ],
];
