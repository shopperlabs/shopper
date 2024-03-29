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
        'category-index' => Livewire\Pages\Category\Index::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Livewire Components
    |--------------------------------------------------------------------------
    */

    'components' => [
        'slide-overs.category-form' => Livewire\SlideOvers\CategoryForm::class,
        'slide-overs.re-order-categories' => Livewire\SlideOvers\ReOrderCategories::class,
    ],

];
