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
        'campaign-index' => Livewire\Pages\Campaign\Index::class,
        'campaign-create' => Livewire\Pages\Campaign\Create::class,
        'campaign-edit' => Livewire\Pages\Campaign\Edit::class,
    ],

];
