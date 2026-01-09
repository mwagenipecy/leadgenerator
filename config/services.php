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

    'outlook' => [
        'client_id' => env('OUTLOOK_CLIENT_ID'),
        'client_secret' => env('OUTLOOK_CLIENT_SECRET'),
        'tenant_id' => env('TENANT_ID'),
        'shared_mailbox' => env('SHARED_MAILBOX'),
        'from_email' => env('FROM_EMAIL'),
    ],

    'creditinfo' => [
        'endpoint' => env('CREDITINFO_ENDPOINT'),
        'username' => env('CREDITINFO_USERNAME'),
        'password' => env('CREDITINFO_PASSWORD'),
        'strategy_id' => env('CREDITINFO_STRATEGY_ID'),
        'connector_id' => env('CREDITINFO_CONNECTOR_ID'),
    ],

    'soap' => [
        'url' => env('SOAP_URL'),
        'username' => env('SOAP_USERNAME'),
        'password' => env('SOAP_PASSWORD'),
    ],

];
