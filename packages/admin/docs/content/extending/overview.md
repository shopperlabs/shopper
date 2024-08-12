# Extending Shopper
Although Shopper is primarily designed for e-commerce sites and has many associated features, you are free to add or modify existing ones.

## Why extends Shopper
One might ask, knowing that the purpose of setting up an e-commerce site is simply to make the whole system work without always wanting to add to it.

For example if you want a blog on your store, a section for a KYC, or maybe a forum, instead of creating another project to manage this you can directly extend Shopper to manage all this in the same administration area.

## How to extend Shopper
To add new features to Shopper, the first thing you need to do is to register a new element in the sidebar by creating an event (for example `RegisterCustomMenu`) and load it directly into your `AppServiceProvider` like this

```php
namespace App\Providers;

use Shopper\Framework\Events\BuildingSidebar; // [tl! focus]

class AppServiceProvider extends ServiceProvider
{
  public function registerCustomShopperSidebar() // [tl! focus]
  { // [tl! focus]
    $this->app['events']->listen(BuildingSidebar::class, RegisterCustomMenu::class); // [tl! focus]
  } // [tl! focus]
}
```

The rest of the configuration will be done using the configuration files in the `config/shopper/` folder

- `/routes.php` for the routes that should be dynamically loaded for the admin panel
- `/system.php` for the controllers that should be loaded for each route.

We will deal with all this in more detail later.

## How not to extend Shopper
It should go without saying — but we'll say it anyway just in case...

Don't ever, for any reason, ever, no matter what, no matter where, or who, or who you are with, or where you are going or... or where you've been... ever, for any reason, whatsoever, edit the files inside `/vendor/shopper`. Or any other Composer package. Anything you do will get blown away and you'll lose those changes forever and ever amen.

You should instead build addons, extensions, and submit pull requests to [core](https://github.com/shopperlabs/shopper) (after checking with the team first if we'll accept them). Thanks!
