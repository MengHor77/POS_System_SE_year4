<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Defaults
    |--------------------------------------------------------------------------
    |
    | Default guard and password broker for the application.
    |
    */

    'defaults' => [
        'guard' => env('AUTH_GUARD', 'web'),
        'passwords' => env('AUTH_PASSWORD_BROKER', 'users'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Authentication Guards
    |--------------------------------------------------------------------------
    |
    | Define all authentication guards. Here we have 'web' for normal users
    | and 'admin' for admin users.
    |
    */

'guards' => [
    'web' => [
        'driver' => 'session',
       'provider' => 'cashiers',
    ],

    'admin' => [
        'driver' => 'session',
        'provider' => 'admins',
    ],
],

/*
|--------------------------------------------------------------------------
| User Providers
|--------------------------------------------------------------------------
|
| Defines how users and admins are retrieved from database.
|
*/

'providers' => [
    'cashiers' => [ // Rename or add this provider
        'driver' => 'eloquent',
        'model' => App\Models\Cashier::class,
    ],
    'admins' => [
            'driver' => 'eloquent',
            'model' => App\Models\Admin::class,
        ],
],

    /*
    |--------------------------------------------------------------------------
    | Resetting Passwords
    |--------------------------------------------------------------------------
    |
    | Password reset configuration. Admins not included yet, can be added later.
    |
    */

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => env('AUTH_PASSWORD_RESET_TOKEN_TABLE', 'password_reset_tokens'),
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Password Confirmation Timeout
    |--------------------------------------------------------------------------
    |
    | How long before password confirmation times out.
    |
    */

    'password_timeout' => env('AUTH_PASSWORD_TIMEOUT', 10800),

];
