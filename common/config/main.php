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
        'emailService' => [
            'class' => 'box\components\EmailService'
        ],
        'notificationComponent' => [
            'class' => box\components\NotificationComponent::class
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'authManager' => [
            'class' => 'common\components\AuthManager',
            'itemFile' => '@common/components/rbac/items.php',
            'assignmentFile' => '@common/components/rbac/assignments.php',
            'ruleFile' => '@common/components/rbac/rules.php'
        ],

    ],
];
