<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Caching
    |--------------------------------------------------------------------------
    |
    | Here you may choose how the sidebar is cached. "static" shares one copy
    | with every user, so items hidden by permissions follow the first user
    | who loads it. "user-based" keeps a copy per user and needs a cache store
    | that supports tags. The duration is expressed in minutes.
    |
    | Supported: null, "static", "user-based"
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

    'width' => '15rem',

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

    /*
    |--------------------------------------------------------------------------
    | Breadcrumbs
    |--------------------------------------------------------------------------
    |
    | Configure the breadcrumb trail. `view` is the Blade view used to render
    | the trail — override it from your own service provider to ship a themed
    | version (the Shopper admin does exactly that). Any project consuming the
    | standalone sidebar package gets the default unstyled trail out of the box.
    |
    */

    'breadcrumbs' => [
        'view' => 'sidebar::breadcrumbs',
    ],

];
