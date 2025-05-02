<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Broadcaster
    |--------------------------------------------------------------------------
    |
    | This option controls the default broadcaster that will be used by the
    | framework when an event needs to be broadcast. You may set this to
    | any of the connections defined in the "connections" array below.
    |
    | Supported: "pusher", "ably", "redis", "log", "null"
    |
    */

    'default' => env('BROADCAST_DRIVER', 'null'),

    /*
    |--------------------------------------------------------------------------
    | Broadcast Connections
    |--------------------------------------------------------------------------
    |
    | Here you may define all of the broadcast connections that will be used
    | to broadcast events to other systems or over websockets. Samples of
    | each available type of connection are provided inside this array.
    |
    */

    'connections' => [
        'default' => env('BROADCAST_DRIVER', 'pusher'),
        'pusher' => [
            'driver' => 'pusher',
            'key' => '746d9aa45990c6441201',
            'secret' => '50d543d287077ebd390d',
            'app_id' => '1984939',
            'options' => [
                'cluster' =>'mt1',
                'useTLS' => false,
                'host' => '127.0.0.1',
                'port' => '6001',
                'scheme' => 'http',
                'encrypted' => false,
            ],
        ],
        'ably' => [
            'driver' => 'ably',
            'key' => env('ABLY_KEY'),
        ],

        'redis' => [
            'driver' => 'redis',
            'connection' => 'default',
        ],

        'log' => [
            'driver' => 'log',
        ],

        'null' => [
            'driver' => 'null',
        ],

    ],

];
