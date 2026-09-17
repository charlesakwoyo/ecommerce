<?php

return [

    /*
    |--------------------------------------------------------------------------
    | M-Pesa Daraja API Environment
    |--------------------------------------------------------------------------
    |
    | Either "sandbox" or "production". Controls which Safaricom API base
    | URL is used for OAuth, STK push, and status query requests.
    |
    */

    'env' => env('MPESA_ENV', 'sandbox'),

    'base_url' => env('MPESA_ENV', 'sandbox') === 'production'
        ? 'https://api.safaricom.co.ke'
        : 'https://sandbox.safaricom.co.ke',

    /*
    |--------------------------------------------------------------------------
    | Daraja App Credentials
    |--------------------------------------------------------------------------
    |
    | From your app on https://developer.safaricom.co.ke. The shortcode and
    | passkey below default to Safaricom's published sandbox test values for
    | the Lipa Na M-Pesa Online (shortcode 174379) sandbox app; override them
    | in .env for your own app or for production.
    |
    */

    'consumer_key' => env('MPESA_CONSUMER_KEY'),
    'consumer_secret' => env('MPESA_CONSUMER_SECRET'),
    'shortcode' => env('MPESA_SHORTCODE', '174379'),
    'passkey' => env('MPESA_PASSKEY'),

    /*
    |--------------------------------------------------------------------------
    | Callback URL
    |--------------------------------------------------------------------------
    |
    | Safaricom posts the STK push result here asynchronously. It must be a
    | publicly reachable HTTPS URL — plain localhost will not work. Use a
    | tunnel such as ngrok in local development.
    |
    */

    'callback_url' => env('MPESA_CALLBACK_URL', env('APP_URL').'/mpesa/callback'),

];
