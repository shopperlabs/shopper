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
        'order-index' => Livewire\Pages\Order\Index::class,
        'order-detail' => Livewire\Pages\Order\Detail::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Livewire Components
    |--------------------------------------------------------------------------
    */

    'components' => [
        'modals.archived-order' => Livewire\Modals\ArchiveOrder::class,
    ],

];
