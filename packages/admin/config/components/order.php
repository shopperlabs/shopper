<?php

declare(strict_types=1);

use Shopper\Livewire\Components;
use Shopper\Livewire\Pages;

return [

    'pages' => [],

    'components' => [
        'orders.browse' => Components\Orders\Browse::class,
        'orders.show' => Components\Orders\Show::class,
    ],

];
