<?php

namespace box\services\search;

use box\entities\shop\Brand;
use Elasticsearch\Client;

class BrandIndexer
{
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function clear(): void
    {
        $this->client->deleteByQuery([
            'index' => 'watch_brands',
            'type' => 'brands',
            'body' => [
                'query' => [
                    'match_all' => new \stdClass(),
                ],
            ],
        ]);
    }

    public function index(Brand $brand): void
    {
        $this->client->index([
            'index' => 'watch_brands',
            'type' => 'brands',
            'id' => $brand->id,
            'body' => [
                'name' => $brand->name,
            ],
        ]);
    }

    public function remove(Brand $brand): void
    {
        $this->client->delete([
            'index' => 'watch_brands',
            'type' => 'brands',
            'id' => $brand->id,
        ]);
    }
}