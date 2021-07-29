<?php

return [
    'provider' => env('SMS_PROVIDER'),
    'kavenegar' => [
        'api' => env('SMS_API'),
        'sender' => env('SMS_SENDER'),
    ],
    'armaghan' => [
        'username' => env('SMS_USERNAME'),
        'password' => env('SMS_PASSWORD'),
        'sender' => env('SMS_SENDER'),
    ]
];
