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

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'planetf' => [
        'validation_url' => env('PLANET_F_VALIDATION_URL'),
        'api_key'        => env('PLANET_F_API_KEY'),
        'airtime_url'    => env('PLANET_F_AIRTIME_URL', 'https://planetf.com.ng/api/v2/bulk/airtime'),
        'data_url'       => env('PLANET_F_DATA_URL', 'https://planetf.com.ng/api/v2/bulk/data'),
        'data_token'     => env('PLANET_F_DATA_TOKEN'),
    ],

];
