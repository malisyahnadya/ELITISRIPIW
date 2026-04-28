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

    'tmdb' => [
        // TMDB v3 API key.
        'api_key' => env('TMDB_API_KEY'),
        'key' => env('TMDB_API_KEY'),

        // Optional TMDB read access token. You can use this instead of API key.
        'access_token' => env('TMDB_ACCESS_TOKEN', env('TMDB_BEARER_TOKEN')),
        'token' => env('TMDB_ACCESS_TOKEN', env('TMDB_BEARER_TOKEN')),

        'base_url' => env('TMDB_BASE_URL', 'https://api.themoviedb.org/3'),
        'image_base_url' => env('TMDB_IMAGE_BASE_URL', 'https://image.tmdb.org/t/p'),
        'language' => env('TMDB_LANGUAGE', 'en-US'),
        'poster_size' => env('TMDB_POSTER_SIZE', 'w500'),
        'banner_size' => env('TMDB_BANNER_SIZE', 'original'),
    ],
];
