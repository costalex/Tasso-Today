<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, SparkPost and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
    ],

    'ses' => [
        'key' => env('SES_KEY'),
        'secret' => env('SES_SECRET'),
        'region' => 'us-east-1',
    ],

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'stripe' => [
        'model' => App\User::class,
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],

	'facebook' => [
		'client_id' => '1190090754458925',
		'client_secret' => 'afac5ad83735cef2ba914568f5e077b3',
		'redirect' => env('APP_URL') . "/api/login/facebook/callback",
	],

	'google' => [
		'client_id' => '710725823502-5emt09u7iel3kgmi0p55o52e89adbohh.apps.googleusercontent.com',
		'client_secret' => 'yw-oGqvftzjIPHPRGu5e9fFS',
		'api_key' => env('GOOGLE_API_KEY'),
		'redirect' => env('APP_URL') . "/api/login/google/callback",
	],

	'ortoo' => [
		'key' => env('ORTOO_KEY')
	]
];
