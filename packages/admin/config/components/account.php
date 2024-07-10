<?php

declare(strict_types=1);

use Shopper\Livewire\Components;
use Shopper\Livewire\Pages;

return [

    /*
    |--------------------------------------------------------------------------
    | Livewire Pages
    |--------------------------------------------------------------------------
    */

    'pages' => [
        'account-index' => Pages\Account::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Livewire Components
    |--------------------------------------------------------------------------
    */

    'components' => [
        'account.devices' => Components\Account\Devices::class,
        'account.dropdown' => Components\Account\Dropdown::class,
        'account.password' => Components\Account\Password::class,
        'account.profile' => Components\Account\Profile::class,
        'account.two-factor' => Components\Account\TwoFactor::class,
    ],

];
