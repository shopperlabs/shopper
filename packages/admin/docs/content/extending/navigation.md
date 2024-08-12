# Navigation
The Control Panel navigation is quite customizable. You can add your own sections, pages, and subpages, as well as remove and modify existing ones.

The navigation is controlled by the package [maatwebsite/laravel-sidebar](https://github.com/SpartnerNL/Laravel-Sidebar). You need to create a sidebar to add your menus within the main sidebar of Shopper. You may register your sidebar like event in the `register()` method of a service provider.

## Adding Navigation
Let’s assume we’re creating a Sidebar folder under app folder, and want to add a Blog navigation item to the Content section of the navigation. To add this item, we’ll create first a `BlogSidebar` class.

There is no command to create this class so you have to create it manually and it must extend from `Shopper\Framework\Sidebar\AbstractAdminSidebar`

We will have an architecture similar to this one

``` files theme:serendipity-light
app/
    Sidebar/
        BlogSidebar.php
```

:::info
We assume here that you have already seen how to add routes for our administration explained in this [section](/extending/control-panel#adding-control-panel-routes).
:::

Here we will assume that our `routes/shopper.php` file contains this

```php
use Illuminate\Support\Facades\Route;

Route::prefix('blog')->group(function () {
    Route::resource('posts', 'PostController')->only(['index', 'create', 'edit']);
});
```

:::warning
You cannot use the notation `[PostController::class, 'index']` because this will not load and will generate an error. Everything is loaded directly from the RouteServiceProvider of Shopper
:::

Our BlogSidebar will look like this

```php
namespace App\Sidebar;

use Maatwebsite\Sidebar\Group;
use Maatwebsite\Sidebar\Item;
use Maatwebsite\Sidebar\Menu;
use Shopper\Framework\Sidebar\AbstractAdminSidebar;

class BlogSidebar extends AbstractAdminSidebar
{
    /**
     * Method used to define your sidebar menu groups and items.
     *
     * @param  Menu  $menu
     * @return Menu
     */
    public function extendWith(Menu $menu): Menu
    {
        $menu->group(__('Blog'), function (Group $group) {
            $group->weight(22);
            $group->authorize(true);

            $group->item(__('Posts'), function (Item $item) {
                $item->weight(2);
                $item->authorize(true);
                $item->route('posts.index');
                $item->icon('
                    <svg class="flex-shrink-0 -ml-1 mr-3 h-5 w-5" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                        <path d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                    </svg>
                ');
            });
        });

        return $menu;
    }
}
```

Now we will register our sidebar in our **AppServiceProvider** with the `register()` method

```php
namespace App\Providers;

use App\Sidebar\BlogSidebar; // [tl! focus]
use Darryldecode\Cart\Cart;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Shopper\Framework\Events\BuildingSidebar; // [tl! focus]

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->isLocal()) {
            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
        }

        $this->app['events']->listen(BuildingSidebar::class, BlogSidebar::class); // [tl! focus]

        $this->app->singleton('wishlist', function ($app) {
            $storage = $app['session'];
            $events = $app['events'];
            $instanceName = 'cart_2';

            return new Cart(
                $storage,
                $events,
                $instanceName,
                session()->getId(),
                config('shopping_cart')
            );
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Str::macro('readDuration', function (...$text) {
            $totalWords = str_word_count(implode(' ', $text));
            $minutesToRead = round($totalWords / 200);

            return (int) max(1, $minutesToRead);
        });
    }
}
```

We will have this in our sidebar

<div class="screenshot">
    <img src="/img/screenshots/{{version}}/sidebar-screen.png" alt="Blog sidebar">
    <div class="caption">Blog sidebar</div>
</div>

## The Menu & Item Class
Each item you see in the navigation is an instance of the `Maatwebsite\Sidebar\Item` class. Each top-level `Menu` in a section can contain its own group of `Item` children.

### Basic API
The code examples above demonstrate how to add Navigation. Once you have a `Item` object, the following chainable methods are available to you:

| Method | Parameters | Description |
| :--- | :--- | :--- |
| `group()` | `$name` (string) | Define group name. |
| `name()` | `$name` (string) | Define item name to overwrite the `group()` name value. |
| `weight()` | `$weight` (int) | Define item order name. |
| `route()` | `$name` (string) | Define a route automatically available in `routes/shopper.php` |
| `url()` | `$url` (string) | Define a URL instead of a route. A string without a leading slash will be relative from the CP. A leading slash will be relative from the root. You may provide an absolute URL. |
| `icon()` | `$icon` (string) | Define icon. |
| `authorize()` | `$ability` (boolean) | Define authorization. |
