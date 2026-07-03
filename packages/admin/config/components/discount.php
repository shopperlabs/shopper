<?php

declare(strict_types=1);

use Shopper\Livewire;

return [

    /*
    |--------------------------------------------------------------------------
    | Livewire Pages
    |--------------------------------------------------------------------------
    */

    'pages' => [
        'discount-index' => Livewire\Pages\Discount\Index::class,
        'discount-edit' => Livewire\Pages\Discount\Edit::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Livewire Components
    |--------------------------------------------------------------------------
    */

    'components' => [
        'slide-overs.add-promotion' => Livewire\SlideOvers\AddPromotion::class,
        'slide-overs.products-picker' => Livewire\SlideOvers\ProductsPicker::class,
        'slide-overs.customers-picker' => Livewire\SlideOvers\CustomersPicker::class,
        'discounts.stats-panel' => Livewire\Components\Discounts\StatsPanel::class,
    ],

];
