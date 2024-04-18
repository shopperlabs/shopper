<?php

declare(strict_types=1);

use Shopper\Livewire;
use Shopper\Livewire\Components;

return [

    /*
    |--------------------------------------------------------------------------
    | Livewire Pages
    |--------------------------------------------------------------------------
    */

    'pages' => [
        'customer-index' => Livewire\Pages\Customers\Index::class,
        'customer-create' => Livewire\Pages\Customers\Create::class,
        'customer-show' => Livewire\Pages\Customers\Show::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Livewire Components
    |--------------------------------------------------------------------------
    */

    'components' => [
        'customers.addresses' => Components\Customers\Addresses::class,
        'customers.orders' => Components\Customers\Orders::class,
        'customers.profile' => Components\Customers\Profile::class,
    ],

];
