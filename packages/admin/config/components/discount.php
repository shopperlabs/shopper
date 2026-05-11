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
        'discount-create' => Livewire\Pages\Discount\Create::class,
        'discount-edit' => Livewire\Pages\Discount\Edit::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Livewire Components
    |--------------------------------------------------------------------------
    */

    'components' => [
        'slide-overs.discount-form' => Livewire\SlideOvers\DiscountForm::class,
        'slide-overs.discount-products-picker' => Livewire\SlideOvers\DiscountProductsPicker::class,
        'slide-overs.discount-customers-picker' => Livewire\SlideOvers\DiscountCustomersPicker::class,
        'discounts.items-list' => Livewire\Components\Discounts\ItemsList::class,
        'discounts.stats-panel' => Livewire\Components\Discounts\StatsPanel::class,
    ],

];
