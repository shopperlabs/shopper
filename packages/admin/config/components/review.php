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
        'reviews.browse' => Components\Reviews\Browse::class,
        'reviews.show' => Components\Reviews\Show::class,

        'modals.delete-review' => Livewire\Modals\DeleteReview::class,
    ],

];
