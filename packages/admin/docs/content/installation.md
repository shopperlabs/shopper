# Installation

Quick start guide for installing and configuring Laravel Shopper on your existing Laravel App

## Supported Versions of Laravel

**Laravel 10 and Laravel 11 are supported.** It feels like this section needs more than one sentence but it really doesn't. That first one said all that needs saying.

## Install Shopper

Shopper is really easy to install. After creating your new app or in an existing Laravel app \(10+\). There are 2 steps to follow to install Shopper.

1. Run `php artisan config:clear` to make sure your config isn't cached.
2. Install `shopper/framework` package with Composer.
  ``` bash
  composer require shopper/framework --with-dependencies
  ```

After installing all dependencies in your project via compose and setup the database, now we will automatically install by running the following commands in your Laravel project directory:
```bash
  php artisan shopper:install
```

This will install shopper, publish vendor files, create shopper and storage symlinks if they don't exist in the public folder, run migrations and seeders classes.

:::info
By default, all shopper tables are prefixed with `sh_` to avoid conflicts with existing tables in your database.
But you can update this configuration according to your need
:::

And we're all good to go!

## Prepare your User Model

Extend your current User Model \(usually `app/Models/User.php`\) using the `Shopper\Core\Models\User as Authenticatable` alias:

```php filename=app/Models/User.php

use Shopper\Core\Models\User as Authenticatable; 

class User extends Authenticatable
{
  // ...
}
```

### Create an Admin user

Now we can create a new superuser and sign in into the Dashboard and start creating some content.
Run the following command to create a user with supreme \(at the moment of creation\) rights:

```bash
php artisan shopper:user
```

## New Shopper Directory

After Shopper is installed, you'll have 1 new directory in your project:
- `config/shopper/`

## Publish Vendor Files

If you want to publish again Shopper's vendor files run these commands:

```bash
php artisan shopper:publish
```

To run the project you may use the built-in server: `php artisan serve`

After that, run `composer dump-autoload` to finish your installation!

If using Laravel Valet or Laravel Herd you can easily access with your project name with `.test` at the end when you navigate on you project.

```bash
http://laravelshopper.test/cpanel/login
```
