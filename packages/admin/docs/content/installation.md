# Installation

Quick start guide for installing and configuring Laravel Shopper on your existing Laravel App

## Supported Versions of Laravel

**Laravel 10 and Laravel 11 are supported.** It feels like this section needs more than one sentence but it really doesn't. That first one said all that needs saying.

## Install Shopper

Shopper is really easy to install. After creating your new app or in an existing Laravel app \(8+\). There are 2 steps to follow to install Shopper.

1. Run `php artisan config:clear` to make sure your config isn't cached.
2. Install `shopper/framework` with Composer.
  ``` bash
  composer require shopper/framework --with-dependencies
  ```

## Write Env Variables

Next make sure to create a new database and add your database credentials to your .env file, you will also want to add your application URL in the `APP_URL` variable
```bash
APP_URL=http://laravelshopper.test
DB_HOST=localhost
DB_DATABASE=homestead
DB_USERNAME=homestead
DB_PASSWORD=secret
```

## Automatic Installation

After installing Shopper in your project via compose and configuring the database, now we will automatically install in the project.
```bash
  php artisan shopper:install
```

This will install shopper, publish vendor files, create shopper and storage symlinks if they don't exist in the public folder, run migrations and seeders classes.

And we're all good to go!

## Update Existing Files

Extend your current User Model \(usually `app/Models/User.php`\) using the `Shopper\Core\Models\User as Authenticatable` alias:

```php
// app/Models/User.php

use Shopper\Core\Models\User as Authenticatable; 

class User extends Authenticatable
{
  // ...
}
```

## Create an Admin user

Now we can create a new superuser and sign into the Dashboard and start creating some content to display on the frontend.

Run the following command to create a user with supreme \(at the moment of creation\) rights:
```bash
php artisan shopper:user
```

And you will be prompted for the user email, firstname, lastname and password. You can now login to start create products

<div class="screenshot">
  <img src="/img/screenshots/{{version}}/product-screenshot.png" alt="Product creation screenshot">
</div>

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
