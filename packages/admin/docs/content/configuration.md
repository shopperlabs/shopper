# Configuration

Shopper uses standard Laravel config files and environment variables for application-level settings

## Config Files

With the installation of Shopper you will find new configurations files located in `config/shopper/`. They are PHP files named by area of responsibility.

``` files theme:github-light
config/shopper/
    admin.php
    auth.php
    components.php
    core.php
    media.php
    models.php
    routes.php
    settings.php
```

And the `admin.php` is the main file, you can find various options to change the configuration of your Shopper installation.

## System
All the basic configurations for using shopper can be found in this file. The management of the locale, the models to use and additional resources (scripts and styles) to the administration.

### Brand logo
By default, the Shopper logo will be used as the brand logo in the administration panel.

To update it you just have to fill in the logo link in your public folder

```php
/*
  |--------------------------------------------------------------------------
  | Brand Name
  |--------------------------------------------------------------------------
  |
  | This will be displayed on the login page and in the sidebar's header.
  |
  */

  'brand' => '/img/screenshots/{{version}}/logo.svg',
```

### Controllers
By default Shopper loads controllers that are defined in this namespace. You can change it if you have a different structure. These controllers are used to extend and add functionalities in the administration.

In your config file you have the controllers key that define the controller's namespace for your extended Controllers:

``` php
'controllers' => [
    'namespace' => 'App\\Http\\Controllers\\Shopper',
],
```

### Models
Models used are defined in the models key, if you want to use your own models you can replace them on the `models.php` config file.

``` php

  'brand' => \Shopper\Core\Models\Brand::class,

  'category' => \Shopper\Core\Models\Category::class,

  'collection' => \Shopper\Core\Models\Collection::class,

  'product' => \Shopper\Core\Models\Product::class,

```

### Additional resources
During your work you may need to add your own style tables or javascript scenarios globally for all the pages, so you need to add them to relevant arrays.

```php
'resources' => [
    'stylesheets' => [
        //'css/custom.css',
    ],
    'scripts' => [
        //'js/custom.js',
    ],
],
```

## Routes
The configuration of the routes allows you to specify a prefix to access your dashboard, the addition of middleware and the configuration file to add more routes to your administration.

### Prefix

```php
// config/shopper/admin.php
'prefix' => env('SHOPPER_PREFIX', 'cpanel'),
```

The system installed on the website can be easily defined by the dashboard prefix, for example it is `wp-admin` for WordPress, and it gives an opportunity to automatically search for old vulnerable versions of software and gain control over it.

There are other reasons but we won't speak of them in this section. The point is that Shopper allows to change dashboard prefix to every other name, `admin` or `administrator` for example.

### Middleware

```php
// config/shopper/routes.php
'middleware' => [],
```

Shopper gives you the ability to add middleware to all of your routes. These middlewares will be applied to all the routes of your administration.

### Additional dashboard routes

```php
// Eg.: base_path('routes/shopper.php')
'custom_file' => null,
```

By default none of your routes in the `web.php` file will be accessible and loaded in the shopper administration. So that your routes added in the sidebar can have the middleware applied to the dashboard, you must fill in an additional routing file and this will be automatically loaded by Shopper's internal RouteServiceProvider.

## Components
The main features of Laravel Shopper is to handle Livewire components to add new functionnalities to your admin panel. 

For this purpose you have a component file that lists all Livewire components used within Laravel Shopper. You can for each feature modify the associated or extends component to add functionality or even change the view to fit your own logic.

Here is an example of some components

```php
use Shopper\Http\Livewire\Components;

return [
    /*
    |--------------------------------------------------------------------------
    | Livewire Components
    |--------------------------------------------------------------------------
    |
    | Below you reference all the Livewire components that should be loaded
    | for your app. By default all components from Shopper Kit are loaded in.
    |
    */
    'livewire' => [
        'account.devices' => Components\Account\Devices::class,
        'account.dropdown' => Components\Account\Dropdown::class,
        'account.password' => Components\Account\Password::class,
    ],
];
```

## Settings
Settings are a very important part of an e-commerce site administration. Shopper has understood this very well and has set up a settings file, to allow you to add or modify the default settings of Shopper.

In this file you can add parameters or delete those you don't need to simplify your store or to make it larger

```php
return [

    /*
    |--------------------------------------------------------------------------
    | Setting Menu
    |--------------------------------------------------------------------------
    |
    | The menu for the generation of the page settings and layout.
    | BladeUIKit Heroicon is the icon used. See https://blade-ui-kit.com/blade-icons?set=1
    |
    */

    'items' => [
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

    ],
];
```

## Mapbox
Shopper uses Mapbox to enter the geographic coordinates (latitude and longitude) of your store so that you can easily tell your customers your location.

To activate mapbox you need to go to the [API](https://docs.mapbox.com/mapbox-gl-js/api/) documentation and create an API token. Once this is done you need to add the key `MAPBOX_PUBLIC_TOKEN` with the token value to your `.env` file

```bash
MAPBOX_PUBLIC_TOKEN=your_token_here
```

## Update Configurations
In your `config/filesystems.php` config file add the following to the disks and links section:

``` php
'disks' => [
    // Shopper Uploads Disks. [tl! highlight:13]
    'avatars' => [ // [tl! collapse:start]
        'driver' => 'local',
        'root' => storage_path('app/avatars'),
        'url' => env('APP_URL').'/avatars',
        'visibility' => 'public',
    ], // [tl! collapse:end]

    'uploads' => [ // [tl! collapse:start]
        'driver' => 'local',
        'root' => storage_path('app/uploads'),
        'url' => env('APP_URL').'/uploads',
        'visibility' => 'public',
    ], // [tl! collapse:end]

],

/*
|--------------------------------------------------------------------------
| Symbolic Links
|--------------------------------------------------------------------------
|
| Here you may configure the symbolic links that will be created when the
| `storage:link` Artisan command is executed. The array keys should be
| the locations of the links and the values should be their targets.
|
*/

'links' => [
    // [tl! highlight:2]
    public_path('avatars') => storage_path('app/avatars'),
    public_path('uploads') => storage_path('app/uploads'),
],

```

### Create New Folders
After adding the 2 entries in the filesystem config file, you must create them and add them to the .gitignore file.
In your storage directory create 2 new folders called `avatars` and `uploads`.

```bash
mkdir storage/app/avatars && mkdir storage/app/uploads
```

In each new folder that you have created (avatars and uploads) you must create a .gitignore file which will contain the following line

```bash
*
!.gitignore
```
