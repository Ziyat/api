 <?php
return [
    [
        'user_id' => 1,
        'token' => 'token-correct',
        'expired_at' => time() + 3600
    ],

    [
        'user_id' => 2,
        'token' => 'token-expired',
        'expired_at' => time() - 3600
    ],

    [
        'user_id' => 2,
        'token' => 'token-correct-id-2',
        'expired_at' => time() + 3600
    ],
    [
        'user_id' => 3,
        'token' => 'token-correct-id-3',
        'expired_at' => time() + 3600
    ],
];
