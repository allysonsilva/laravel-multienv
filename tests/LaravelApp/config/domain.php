<?php

return [
    'name' => env('DOMAIN_NAME'),
    'env' => env('DOMAIN_ENV'),
    'debug' => env('DOMAIN_DEBUG'),
    'url' => env('DOMAIN_URL', 'http://domain.test'),
    'key' => env('DOMAIN_KEY'),

    'app' => [
        'name' => env('APP_NAME'),
        'env' => env('APP_ENV'),
        'debug' => env('APP_DEBUG'),
    ],
];
