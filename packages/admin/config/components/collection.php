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

    'pages' => [],

    /*
    |--------------------------------------------------------------------------
    | Livewire Components
    |--------------------------------------------------------------------------
    */

    'components' => [
        'collections.browse' => Components\Collections\Browse::class,
        'collections.create' => Components\Collections\Create::class,
        'collections.edit' => Components\Collections\Edit::class,
        'collections.products' => Components\Collections\Products::class,
    ],

];
