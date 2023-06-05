<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Shopper Guard
    |--------------------------------------------------------------------------
    |
    | Here you may specify which authentication guard Shopper will use while
    | authenticating users. This value should correspond with one of your
    | guards that is already present in your "auth" configuration file.
    |
    */

    'guard' => 'web',

    /*
    |--------------------------------------------------------------------------
    | Two Factor Authentication
    |--------------------------------------------------------------------------
    |
    | Enabled 2FA authentication during login on the shopper dashboard.
    |
    */

    '2fa_enabled' => env('SHOPPER_ENABLED_TWO_FACTOR', true),

];
