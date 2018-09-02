<?php

namespace box\services\search;

use box\entities\user\User;
use Elasticsearch\Client;

class UserIndexer
{
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function clear(): void
    {
        $this->client->deleteByQuery([
            'index' => 'watch_users',
            'type' => 'users',
            'body' => [
                'query' => [
                    'match_all' => new \stdClass(),
                ],
            ],
        ]);
    }

    public function index(User $user): void
    {
        $this->client->index([
            'index' => 'watch_users',
            'type' => 'users',
            'id' => $user->id,
            'body' => [
                'name' => $user->profile->name,
                'lastName' => $user->profile->last_name,
                'dateOfBirth' => $user->profile->date_of_birth,
                'photo' => $user->profile->getPhoto()
            ],
        ]);
    }

    public function remove(User $user): void
    {
        $this->client->delete([
            'index' => 'watch_users',
            'type' => 'users',
            'id' => $user->id,
        ]);
    }
}