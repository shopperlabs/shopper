<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Caching
    |--------------------------------------------------------------------------
    |
    | Define the way the Sidebar should be cached.
    | The cache store is defined by the Laravel
    |
    | Available: null|static|user-based
    |
    */

    'cache' => [
        'method' => null,
        'duration' => 1440,
    ],

    /*
    |--------------------------------------------------------------------------
    | Sidebar Dimensions
    |--------------------------------------------------------------------------
    |
    | Configure the sidebar width for expanded and collapsed states.
    | These values will be injected as CSS variables.
    |
    */

    'width' => '16rem',

    'collapsed_width' => '4.5rem',

    /*
    |--------------------------------------------------------------------------
    | Responsive Breakpoint
    |--------------------------------------------------------------------------
    |
    | The breakpoint (in pixels) below which the sidebar switches to mobile
    | mode (hidden by default, shown via toggle).
    |
    */

    'breakpoint' => 1024,

    /*
    |--------------------------------------------------------------------------
    | Collapsible
    |--------------------------------------------------------------------------
    |
    | Whether the sidebar can be collapsed on desktop.
    |
    */

    'collapsible' => true,

];
