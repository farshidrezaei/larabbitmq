<?php
/*
 * Copyright (c) 2022. Farshid Rezaei
 */


return [
    'connection' => [
        'rabbitmq' => [
            'host' => env('LARABBITMQ_RABBIT_HOST', '127.0.0.1'),
            'port' => env('LARABBITMQ_RABBIT_PORT', '5672'),
            'user' => env('LARABBITMQ_RABBIT_USERNAME', 'guest'),
            'pass' => env('LARABBITMQ_RABBIT_PASSWORD', 'guest'),
        ]
    ],
    'consume-handlers' => [

    ]

];
