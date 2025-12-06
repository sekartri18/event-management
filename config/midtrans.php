<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Midtrans Configuration
    |--------------------------------------------------------------------------
    |
    | This file is for storing the configuration settings for Midtrans payment
    | gateway integration in your application. You can set your server key,
    | client key, environment mode, and other related settings here.
    |
    */

    'server_key' => env('MIDTRANS_SERVER_KEY', ''),

    'client_key' => env('MIDTRANS_CLIENT_KEY', ''),

    'is_production' => env('MIDTRANS_IS_PRODUCTION', true),

    'is_sanitized' => env('MIDTRANS_IS_SANITIZED', true),

    'is_3ds' => env('MIDTRANS_IS_3DS', true),

];