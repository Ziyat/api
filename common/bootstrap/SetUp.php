<?php

namespace common\bootstrap;


use box\components\EmailService;
use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;
use yii\base\BootstrapInterface;

class SetUp implements BootstrapInterface
{
    public function bootstrap($app)
    {
        $container = \Yii::$container;

        $container->setSingleton(Client::class, function () {
            return ClientBuilder::create()->build();
        });
    }

}