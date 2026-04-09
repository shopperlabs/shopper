---
name: shopper-customization
description: Patterns for customizing Shopper in your Laravel application. Sidebar navigation, component overrides, event listeners, and domain features like stock, pricing, and media. Use when extending Shopper behavior in a project.
license: MIT
metadata:
    author: shopperlabs
---

# Extending Shopper

## Sidebar Navigation

### Add Item to Existing Group

```php
// app/Sidebar/ShippingSidebar.php
namespace App\Sidebar;

use Shopper\Sidebar\AbstractAdminSidebar;
use Shopper\Sidebar\Contracts\Builder\Group;
use Shopper\Sidebar\Contracts\Builder\Item;
use Shopper\Sidebar\Contracts\Builder\Menu;

class ShippingSidebar extends AbstractAdminSidebar
{
    public function extendWith(Menu $menu): Menu
    {
        $menu->group(__('shopper::layout.sidebar.catalog'), function (Group $group): void {
            $group->weight(2); // Same weight as CatalogSidebar to merge

            $group->item(__('Shipping'), function (Item $item): void {
                $item->weight(5);
                $item->useSpa();
                $item->route('shopper.shipping.index');
                $item->setIcon('untitledui-truck-01');
                $item->setAuthorized($this->user->hasPermissionTo('browse_shipping'));
            });
        });

        return $menu;
    }
}
```

Register in `AppServiceProvider`:

```php
use Shopper\Sidebar\SidebarBuilder;

public function boot(): void
{
    $this->app['events']->listen(SidebarBuilder::class, ShippingSidebar::class);
}
```

### Create New Sidebar Group

```php
$menu->group(__('Logistics'), function (Group $group): void {
    $group->weight(5); // After CustomerSidebar (weight 4)
    $group->setAuthorized();

    $group->item(__('Shipping'), function (Item $item): void {
        $item->weight(1);
        $item->useSpa();
        $item->route('shopper.shipping.index');
        $item->setIcon('untitledui-truck-01');
    });
});
```

### Default Sidebar Groups

| Group     | Weight | Class              |
|-----------|--------|--------------------|
| Dashboard | 1      | `DashboardSidebar` |
| Catalog   | 2      | `CatalogSidebar`   |
| Sales     | 3      | `SalesSidebar`     |
| Customers | 4      | `CustomerSidebar`  |

### Item Options

- `$item->weight(int)` - Position (lower = higher)
- `$item->useSpa()` - Enable `wire:navigate`
- `$item->route('name')` - Set route
- `$item->setIcon('untitledui-*')` - Icon
- `$item->setAuthorized(bool)` - Visibility condition

## Override Components

Config files in `config/shopper/components/`:

```php
// config/shopper/components/product.php
return [
    'pages' => [
        'product-index' => App\Livewire\Shopper\Products\Index::class,
        'product-edit' => \Shopper\Livewire\Pages\Product\Edit::class,
    ],
    'components' => [
        'products.form.edit' => App\Livewire\Shopper\Products\EditForm::class,
    ],
];
```

Extend the base component:

```php
namespace App\Livewire\Shopper\Products;

use Shopper\Livewire\Pages\Product\Index as BaseIndex;

class Index extends BaseIndex
{
    public function table(Table $table): Table
    {
        return parent::table($table)
            ->columns([
                ...parent::table($table)->getColumns(),
                TextColumn::make('custom_field'),
            ]);
    }
}
```

## Events

Listen to Shopper events:

```php
// EventServiceProvider
protected $listen = [
    \Shopper\Core\Events\Products\ProductCreated::class => [YourListener::class],
    \Shopper\Core\Events\Products\ProductUpdated::class => [],
    \Shopper\Core\Events\Products\ProductDeleted::class => [],
    \Shopper\Core\Events\Orders\OrderCreated::class => [SendOrderConfirmation::class],
    \Shopper\Core\Events\Orders\OrderCompleted::class => [],
    \Shopper\Core\Events\Orders\OrderPaid::class => [],
    \Shopper\Core\Events\Orders\OrderCancelled::class => [],
];
```

## Stock Management

```php
use Shopper\Core\Models\Inventory;

$product = Product::query()->find($id);
$inventory = Inventory::query()->where('is_default', true)->first();

$product->setStock(100, $inventory->id);
$product->decreaseStock($inventory->id, 5);
$currentStock = $product->getStock();
```

## Pricing

Amounts stored in cents, supports multi-currency:

```php
use Shopper\Core\Models\Currency;

$product->prices()->create([
    'currency_id' => Currency::where('code', 'USD')->first()->id,
    'amount' => 2999,         // $29.99
    'compare_amount' => 3999, // $39.99 (crossed-out)
    'cost_amount' => 1500,    // $15.00 (cost)
]);
```

## Media Management

```php
// Add thumbnail
$product->addMedia($file)
    ->toMediaCollection(config('shopper.media.storage.thumbnail_collection'));

// Add gallery images
$product->addMedia($file)
    ->toMediaCollection(config('shopper.media.storage.collection_name'));

// Get URL
$thumbnail = $product->getFirstMediaUrl(config('shopper.media.storage.thumbnail_collection'));
```

## Feature Flags

```php
if (\Shopper\Feature::enabled('review')) {
    // Show reviews
}
```

Configure in `config/shopper/features.php`.

## Helper Functions

| Function                       | Purpose                             |
|--------------------------------|-------------------------------------|
| `shopper_table('products')`    | Prefixed table name (`sh_products`) |
| `shopper_setting('shop_name')` | Get shop setting                    |
| `shopper_fallback_url()`       | Fallback image URL                  |
| `generate_number()`            | Order number with prefix            |
| `shopper()->auth()->user()`    | Authenticated admin                 |

## Render Hooks

Shopper provides 30+ UI injection points to extend admin pages without modifying core views. Register hooks in your ServiceProvider:

```php
use Shopper\Facades\Shopper;
use Shopper\View\ProductRenderHook;

public function boot(): void
{
    Shopper::renderHook(ProductRenderHook::EDIT_CONTENT_AFTER, fn () => view('custom.product-extra'));
}
```

Available hook classes: `LayoutRenderHook`, `ProductRenderHook`, `OrderRenderHook`, `CustomerRenderHook`, `CatalogRenderHook`, `CollectionRenderHook`, `SalesRenderHook`. See documentation for all hook constants.

## Addon System

Build reusable plugins with `BaseAddon`. Register routes, components, sidebar items, permissions, and assets:

```php
use Shopper\Addon\BaseAddon;
use Shopper\ShopperPanel;

class LoyaltyAddon extends BaseAddon
{
    public function getId(): string
    {
        return 'loyalty';
    }

    public function register(ShopperPanel $panel): void
    {
        $panel->addRoutes(fn () => $this->loadRoutesFrom(__DIR__ . '/../routes/web.php'));
        $panel->addSidebar(LoyaltySidebar::class);
        $panel->addPermissions(['browse_loyalty', 'manage_loyalty']);
    }
}
```

See documentation at https://docs.laravelshopper.dev for the complete addon lifecycle and AddonManager API.

## Custom Routes

```php
// config/shopper/routes.php
return [
    'custom_file' => base_path('routes/shopper.php'),
];

// routes/shopper.php
use App\Livewire\Shopper\Shipping;

Route::get('shipping', Shipping::class)->name('shopper.shipping.index');
```
