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
        'categories.browse' => Components\Categories\Browse::class,
        'categories.create' => Components\Categories\Create::class,
        'categories.edit' => Components\Categories\Edit::class,

        'modals.re-order-categories' => Livewire\Modals\ReOrderCategories::class,
    ],

];
