<?php
return [
    'user' => [
        'type' => 1,
        'description' => 'User',
    ],
    'moderator' => [
        'type' => 1,
        'description' => 'moderator',
        'children' => [
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
