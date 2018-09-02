<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace box\readModels;


use box\forms\SearchForm;
use Elasticsearch\Client;
use yii\helpers\Inflector;

class GenericProductReadRepository
{
    public $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }
}