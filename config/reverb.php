<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Reverb Server
    |--------------------------------------------------------------------------
    |
    | This option controls the default server used by Reverb to handle incoming
    | messages as well as broadcasting messages to all of your connected clients.
    | At this time only "reverb" is supported.
    |
    */

    'default' => env('REVERB_SERVER', 'reverb'),

    /*
    |--------------------------------------------------------------------------
    | Reverb Servers
    |--------------------------------------------------------------------------
    |
    | Here you may define details for each of the supported Reverb servers.
    | Each server has its own configuration options that are defined in the
    | array below. You should ensure all the options are present.
    |
    */

    'servers' => [

        'reverb' => [
            'host' => env('REVERB_SERVER_HOST', '0.0.0.0'),
            'port' => (int) env('REVERB_SERVER_PORT', 8081),
            'hostname' => env('REVERB_HOSTNAME', env('REVERB_HOST', 'localhost')),

            'options' => [
                'tls' => [],
            ],

            'max_request_size' => (int) env('REVERB_MAX_REQUEST_SIZE', 10_000),

            'scaling' => [
                'enabled' => (bool) env('REVERB_SCALING_ENABLED', false),
                'channel' => env('REVERB_SCALING_CHANNEL', 'reverb'),
            ],

            'pulse_ingest_interval' => (int) env('REVERB_PULSE_INGEST_INTERVAL', 15),
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Reverb Applications
    |--------------------------------------------------------------------------
    |
    | Here you may define how Reverb applications are managed. If you choose to
    | use the "config" provider, you may define an array of apps which your
    | server will support, including their connection credentials.
    |
    */

    'apps' => [

        'provider' => env('REVERB_APPS_PROVIDER', 'config'),

        'apps' => [

            [
                'key' => env('REVERB_APP_KEY', 'local'),
                'secret' => env('REVERB_APP_SECRET', 'local'),
                'app_id' => env('REVERB_APP_ID', 'local'),

                'options' => [
                    'host' => env('REVERB_HOST', 'localhost'),
                    'port' => (int) env('REVERB_PORT', 8080),
                    'scheme' => env('REVERB_SCHEME', 'http'),
                    'useTLS' => env('REVERB_SCHEME', 'http') === 'https',
                ],

                // Restrict if you want, e.g. [env('APP_URL')]. Default '*' is easiest for local dev.
                'allowed_origins' => ['*'],

                'ping_interval' => (int) env('REVERB_APP_PING_INTERVAL', 60),
                'max_message_size' => (int) env('REVERB_APP_MAX_MESSAGE_SIZE', 10_000),
            ],

        ],

    ],

];
