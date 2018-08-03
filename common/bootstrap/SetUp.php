<?php

namespace common\bootstrap;


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