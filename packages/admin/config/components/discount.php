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
        'discounts.browse' => Components\Discounts\Browse::class,
        'discounts.create' => Components\Discounts\Create::class,
        'discounts.edit' => Components\Discounts\Edit::class,

        'modals.delete-discount' => Livewire\Modals\DeleteDiscount::class,
        'modals.discount-products' => Livewire\Modals\DiscountProducts::class,
        'modals.discount-customers' => Livewire\Modals\DiscountCustomers::class,
    ],

];
