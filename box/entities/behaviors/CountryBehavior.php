<?php

namespace box\entities\behaviors;

use yii\base\Behavior;
use yii\base\Event;
use yii\db\ActiveRecord;
use yii\helpers\Json;

class CountryBehavior extends Behavior
{
    public $attribute = 'countryIds';
    public $jsonAttribute = 'countries';

    public function events(): array
    {
        return [
            ActiveRecord::EVENT_AFTER_FIND => 'onAfterFind',
            ActiveRecord::EVENT_BEFORE_INSERT => 'onBeforeSave',
            ActiveRecord::EVENT_BEFORE_UPDATE => 'onBeforeSave',
        ];
    }

    /**
     * @param Event $event
     * @throws \yii\base\InvalidArgumentException
     */
    public function onAfterFind(Event $event): void
    {
        $model = $event->sender;
        $countries = Json::decode($model->getAttribute($this->jsonAttribute));
        $model->{$this->attribute} = $countries;
    }

    /**
     * @param Event $event
     * @throws \yii\base\InvalidArgumentException
     */
    public function onBeforeSave(Event $event): void
    {
        $model = $event->sender;
        $model->setAttribute('countries', Json::encode($model->{$this->attribute}));
    }
}