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
     'dstgame' => [
        'api_url' => env('DSTGAME_API_URL', 'https://stagingapi.dstgaming.info'),
        'bo_url' => env('DSTGAME_BO_URL', 'https://stagingag.dstgaming.info'),
        'company_code' => env('DSTGAME_COMPANY_CODE', 'oneapi'),
        'api_key' => env('DSTGAME_API_KEY', 'jr9ESLUC'),
        'bo_username' => env('DSTGAME_BO_USERNAME', 'oneapiagent07'),
        'bo_password' => env('DSTGAME_BO_PASSWORD', 'Rocky1122'),
    ],

];
