# Configuration

Shopper uses standard Laravel config files and environment variables for application-level settings.

With the installation of Shopper you will find new configurations files located in `config/shopper/`.
Shopper is a build components blocks, and by default all configuration files are not published and will be published in a `components` folder inside the Shopper configuration folder.

Here's the list of configuration files that are published during installation

``` files theme:github-light
config/shopper/
    admin.php
    auth.php
    core.php
    features.php
    media.php
    models.php
    orders.php
    routes.php
    settings.php
```

### Control Panel

By default, when you install the package, the dashboard is accessible via the prefix `/cpanel`. 
You can update this configuration in the `admin.php` file, or you can add the `SHOPPER_PREFIX` variable to your `.env` environment file.

```php filename=config/shopper/admin.php
'prefix' => env('SHOPPER_PREFIX', 'cpanel'),
```

:::tip
If you update this configuration, you have to republish the assets to take the new link. 
The assets are dynamically loaded by a symbolic link named as the prefix.
```php
php artisan shopper:link
```
:::

If you want to configure a specific domain, you can add a `SHOPPER_DOMAIN` variable to your environment file. 
This config will use [Laravel](https://laravel.com/docs/routing#route-group-subdomain-routing) `Route::domain()` function

```php
'domain' => env('SHOPPER_DOMAIN'),
```

### Database tables prefix

During installation, all tables are prefixed with `sh_` so that they don't conflict with existing tables in your application database. 
If you change this prefix after installation, you'll need to refresh migration for the configuration to take effect.

```php filename=config/shopper/core.php
'table_prefix' => 'sh_',
```

Run laravel refresh migration command: `php artisan migrate:refresh`
Re-run the Shopper Database table seeder: `php artisan db:seed --class=\Shopper\Core\Database\Seeders\ShopperSeeder` 

:::warning
If you do this knowing that you already have data in your database, you risk losing it. Think carefully, because you'll be on your own afterwards.
:::

### Models

Models used are defined in the models config `shopper/models.php` file, if you want to use your own models you can replace them on this file.

```php
use Shopper\Core\Models;

return [
    'brand' => Models\Brand::class,

    'category' => Models\Category::class,

    'collection' => Models\Collection::class,

    'product' => Models\Product::class,

    'channel' => Models\Channel::class,
];
```

### Added Middleware

Shopper gives you the ability to add extra middlewares. All these middlewares will be applied to authenticated routes

```php filename=config/shopper/routes.php
'middleware' => [],
```

### Additional dashboard routes

By default, none of your routes in the `web.php` file will be accessible and loaded in the shopper administration.
Routes added in the sidebar have the middleware applied to the dashboard, you must fill in an additional routing file and this will be automatically loaded by Shopper

```php filename=config/shopper/routes.php
// E.g.: base_path('routes/shopper.php')
'custom_file' => null,
```

### Components

The main features of Shopper is to handle Livewire components to add new functionalities to your admin panel.

For this purpose you have components files that lists each Livewire components used within Laravel Shopper. 
You can extend component to add functionality and even change the view to fit your own logic.

Here is a list of components files available. All these files can be published with `php artisan shopper:component:publish`

```files theme:github-light
config/shopper/components
    account.php
    brand.php
    category.php
    collection.php
    customer.php
    dashboard.php
    discount.php
    order.php
    product.php
    review.php
    setting.php
```

You can publish each file individually, allowing you to replace or customize components. 
All you need to do is issue the same command, but this time specify the name of the configuration file

```bash
php artisan shopper:component:publish brand
```

### Settings

Settings are a very important part of an e-commerce site administration. 
Shopper has understood this very well and has set up a settings file, to allow you to add or modify the default settings of Shopper.

In this file you can add parameters or delete those you don't need to simplify your store or to make it larger

```php
return [
    'items' => [
        // ...
        [
            'name' => 'General',
            'description' => 'View and update your store information.',
            'icon' => 'heroicon-o-cog',
            'route' => 'shopper.settings.shop',
            'permission' => null,
        ],
        [
            'name' => 'Staff & permissions',
            'description' => 'View and manage what staff can see or do in your store.',
            'icon' => 'heroicon-o-users',
            'route' => 'shopper.settings.users',
            'permission' => null,
        ],
        // ...
    ],
];
```

:::warning
Be careful: if you delete items from the configuration, you will no longer have access to certain interfaces, 
and this can make the user experience more complex.
:::
