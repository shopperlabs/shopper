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
        'index' => Livewire\Pages\Brand\Index::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Livewire Components
    |--------------------------------------------------------------------------
    */

    'components' => [
        'slide-overs.brand-form' => Livewire\SlideOvers\BrandForm::class,
    ],

];
