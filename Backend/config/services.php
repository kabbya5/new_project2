<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'game' => [
        'api_url' => env('GAME_URL', 'https://api.playfivers.com/api/v2'),
        'agent_secret_key' => env('AGENT_SECRET_KEY', 'ef767696-7c3e-4712-8be7-1e96619d5d26'),
        'agent_token' => env('AGENT_TOKEN', '2c075cd1-5a6f-4352-827c-aefbaea635be'),
        'agent_password' => env('AGENT_PASSWORD', 'Luckbuzz2888@'),
        'agent_code' => env('AGENT_CODE', 'Luckbuzz99jan'),
    ],

    'lpg' => [
        'app_id'     => env('PAYMENT_APP_ID', 'MJL3061'),
        'secret_key' => env('PAYMENT_SECRET_KEY', 'tFjw42bi7G7xy6cAzgByIheSmRs0zorT'),
        'base_url'   => env('PAYMENT_BASE_URL', 'https://www.lg-pay.com/api'),
    ],

    'oroplay' => [
        'base_url' => env('OROPLAY_BASE_URL'),
        'client_id' => env('OROPLAY_CLIENT_ID'),
        'client_secret' => env('OROPLAY_CLIENT_SECRET'),
    ],
];
