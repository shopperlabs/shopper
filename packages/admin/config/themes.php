<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Default Theme
    |--------------------------------------------------------------------------
    |
    | The theme applied when no theme has been selected in the admin settings.
    | Must match one of the keys registered in the "registered" array below.
    |
    */

    'default' => 'default',

    /*
    |--------------------------------------------------------------------------
    | Registered Themes
    |--------------------------------------------------------------------------
    |
    | Each theme maps to a `[data-theme='id']` block in the compiled admin
    | stylesheet (see resources/css/themes/*.css). Add your own theme by
    | creating a CSS file that overrides the `--theme-sh-*` tokens, importing
    | it in resources/css/shopper.css, then registering it here.
    |
    | Community packages may register themes at runtime through the
    | ThemeManager: `resolve(ThemeManager::class)->register(new Theme(...))`.
    |
    | Keys: name, author, has_dark (theme ships a dark variant),
    | css (optional URL/path to an external stylesheet to load for the theme).
    |
    */

    'registered' => [
        'default' => [
            'name' => 'Default',
            'author' => 'Shopper',
            'has_dark' => true,
        ],
        'midnight' => [
            'name' => 'Midnight',
            'author' => 'Shopper',
            'has_dark' => true,
        ],
        'evergreen' => [
            'name' => 'Evergreen',
            'author' => 'Shopper',
            'has_dark' => true,
        ],
    ],

];
