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

    'pages' => [],

    /*
    |--------------------------------------------------------------------------
    | Livewire Components
    |--------------------------------------------------------------------------
    */

    'components' => [
        'customers.addresses' => Components\Customers\Addresses::class,
        'customers.browse' => Components\Customers\Browse::class,
        'customers.create' => Components\Customers\Create::class,
        'customers.orders' => Components\Customers\Orders::class,
        'customers.profile' => Components\Customers\Profile::class,
        'customers.show' => Components\Customers\Show::class,

        'modals.delete-customer' => Livewire\Modals\DeleteCustomer::class,
    ],

];
