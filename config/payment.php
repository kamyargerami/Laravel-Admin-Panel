<?php

return [
    'default' => env('DEFAULT_PAYMENT', 'zarinpal'),

    'zarinpal' => [
        'merchant' => env('ZARINPAL_MERCHANT'),
        'url' => env('ZARINPAL_URL')
    ]
];
