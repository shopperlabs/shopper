<?php

declare(strict_types=1);

use Shopper\Livewire\Components;

return [

    /*
    |--------------------------------------------------------------------------
    | Livewire Components
    |--------------------------------------------------------------------------
    */

    'steps' => [
        'store-information' => Components\Initialization\Steps\StoreInformation::class,
        'store-address' => Components\Initialization\Steps\StoreAddress::class,
        'store-social-link' => Components\Initialization\Steps\StoreSocialLink::class,
    ],

];
