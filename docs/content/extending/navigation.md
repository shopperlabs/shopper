# Navigation

The Control Panel navigation is quite customizable. You can add your own sections, pages, and subpages, as well as remove and modify existing ones.

The navigation is controlled by the package [shopper/sidebar](https://github.com/shopperlabs/sidebar) who is a fork 
of [maatwebsite/laravel-sidebar](https://github.com/SpartnerNL/Laravel-Sidebar). You need to create a sidebar to add your menus 
within the main sidebar of Shopper. You may register your sidebar like event in the `register()` method of a service provider.

## Adding Navigation

Let’s assume we’re creating a Sidebar folder under app folder, and want to add a Blog navigation item to the Content section of the navigation. 
To add this item, we’ll create first a `BlogSidebar` class.

There is no command to create this class so you have to create it manually and it must extend from `Shopper\Sidebar\AbstractAdminSidebar`

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

Route::prefix('blog')->group(function (): void {
    Route::get('posts', [PostController::class, 'index'])->name('posts');
});
```

:::warning
All routes in `routes/shopper.php` will be called using the shopper prefix. This is injected into a group that has this route alias, eg: `shopper.posts`
:::

Our BlogSidebar will look like this

```php
namespace App\Sidebar;

use Shopper\Sidebar\Contracts\Builder\Group;
use Shopper\Sidebar\Contracts\Builder\Item;
use Shopper\Sidebar\Contracts\Builder\Menu;
use Shopper\Sidebar\AbstractAdminSidebar;

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
        $menu->group(__('Blog'), function (Group $group): void {
            $group->weight(10);
            $group->setAuthorized();
            $group->setGroupItemsClass('space-y-1');
            $group->setHeadingClass('sh-heading');

            $group->item(__('Posts'), function (Item $item): void {
                $item->weight(2);
                $group->setAuthorized();
                $item->setItemClass('sh-sidebar-item group');
                $item->setActiveClass('sh-sidebar-item-active');
                $item->setInactiveClass('sh-sidebar-item-inactive');
                $item->useSpa();
                $item->route('posts');
                $item->setIcon(
                    icon: 'untitledui-tag',
                    iconClass: 'size-5 ' . ($item->isActive() ? 'text-primary-600' : 'text-gray-400 dark:text-gray-500'),
                    attributes: [
                        'stroke-width' => '1.5',
                    ],
                );
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
use Shopper\Sidebar\SidebarBuilder; // [tl! focus]

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app['events']->listen(SidebarBuilder::class, BlogSidebar::class); // [tl! focus]

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

    public function boot(): void
    {
        //
    }
}
```

We will have this in our sidebar

<div class="screenshot">
    <img src="/screenshots/{{version}}/sidebar-screen.png" alt="Blog sidebar">
    <div class="caption">Blog sidebar</div>
</div>

## The Menu & Item Class

Each item you see in the navigation is an instance of the `Shopper\Sidebar\Contracts\Builder\Item` class. 
Each top-level `Menu` in a section can contain its own group of `Item` children.

### Basic API

The code examples above demonstrate how to add Navigation. Once you have a `Item` object, the following chainable methods are available to you:

| Method            | Parameters              | Description                                                                                                                                                                      |
|:------------------|:------------------------|:---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| `group()`         | `$name` (string)        | Define group name.                                                                                                                                                               |
| `weight()`        | `$weight` (int)         | Define item order name.                                                                                                                                                          |
| `route()`         | `$name` (string)        | Define a route automatically available in `routes/shopper.php`                                                                                                                   |
| `setUrl()`        | `$url` (string)         | Define a URL instead of a route. A string without a leading slash will be relative from the CP. A leading slash will be relative from the root. You may provide an absolute URL. |
| `setIcon()`       | `$icon` (string)        | Define icon.                                                                                                                                                                     |
| `setAuthorized()` | `$authorized` (boolean) | Define authorization.                                                                                                                                                            |
