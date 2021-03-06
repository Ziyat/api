 <?php
 $week = 3600 * 24 * 7;
return [
    [
        'user_id' => 1,
        'token' => 'token-correct',
        'expired_at' => time() + $week
    ],

    [
        'user_id' => 2,
        'token' => 'token-expired',
        'expired_at' => time() - $week
    ],

    [
        'user_id' => 2,
        'token' => 'token-correct-id-2',
        'expired_at' => time() + $week
    ],
    [
        'user_id' => 3,
        'token' => 'token-correct-id-3',
        'expired_at' => time() + $week
    ],
    [
        'user_id' => 4,
        'token' => 'token-expired-id-4',
        'expired_at' => time() - $week
    ],
];
