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
        'orders.browse' => Components\Orders\Browse::class,
        'orders.show' => Components\Orders\Show::class,

        'modals.archived-order' => Livewire\Modals\ArchiveOrder::class,
    ],

];
