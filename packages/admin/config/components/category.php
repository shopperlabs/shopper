<?php

declare(strict_types=1);

use Shopper\Livewire\Components;
use Shopper\Livewire\Pages;

return [

    'pages' => [],

    'components' => [
        'categories.browse' => Components\Categories\Browse::class,
        'categories.create' => Components\Categories\Create::class,
        'categories.edit' => Components\Categories\Edit::class,
    ],

];
