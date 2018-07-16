<?php
return [
    'create' => [
        'type' => 2,
    ],
    'user' => [
        'type' => 1,
        'description' => 'User',
    ],
    'moderator' => [
        'type' => 1,
        'description' => 'moderator',
        'children' => [
            'create',
            'user',
        ],
    ],
    'administrator' => [
        'type' => 1,
        'description' => 'administrator',
        'children' => [
            'user',
            'moderator',
        ],
    ],
];
