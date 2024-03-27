# Laravel Sidebar

Laravel sidebar is a package that allow you to create and manage sidebar for your Laravel app.
Originally forked from [SpartnerNL/Laravel-Sidebar](https://github.com/SpartnerNL/Laravel-Sidebar).

## Installation

Require this package in your project by run the command:

```bash
composer require shopper/sidebar
```

Add the package middleware to `App\Http\Kernel`:

```php
`'Shopper\Sidebar\Middleware\ResolveSidebars'`
```

To publish the default views use:

```php
php artisan vendor:publish --tag="views"
```

To publish the config use:

```php
php artisan vendor:publish --tag="config"
```

## Documentation

See the wiki: https://github.com/Maatwebsite/Laravel-Sidebar/wiki

## License

This package is licensed under MIT. You are free to use it in personal and commercial projects. Enjoy!
