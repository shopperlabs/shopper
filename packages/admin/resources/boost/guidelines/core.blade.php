@php
/** @var \Laravel\Boost\Install\GuidelineAssist $assist */
@endphp

## Laravel Shopper

Laravel Shopper is a headless e-commerce framework providing a complete admin panel built with Filament and Livewire. For detailed documentation, refer to https://docs.laravelshopper.dev

### Installation

- Use `{{ $assist->composerCommand('require shopper/framework --with-dependencies') }}` to install Shopper.
- Run `{{ $assist->artisanCommand('shopper:install') }}` to publish config, migrations, and assets.
- Run `{{ $assist->artisanCommand('shopper:user') }}` to create an admin user.
- The admin panel is accessible at `/cpanel` by default (configurable via `SHOPPER_PREFIX` env variable).

### Configuration

Configuration files are published to `config/shopper/`:

- `admin.php` - Admin panel prefix, domain, and custom pages namespace/path
- `core.php` - Table prefix (default: `sh_`), roles
- `models.php` - Model bindings for customization
- `features.php` - Enable/disable features (attributes, collections, reviews, discounts)
- `media.php` - Media storage settings (Spatie Media Library)
- `orders.php` - Order number generation
- `routes.php` - Custom routes and middleware
- `components/` - Component overrides by feature

### Creating Custom Admin Pages

Use `{{ $assist->artisanCommand('make:shopper-page {PageName}') }}` to create a new page in the admin panel. This creates:
- A Livewire component in `App\Livewire\Shopper` namespace (configurable in `config/shopper/admin.php`)
- A Blade view in `resources/views/livewire/shopper`

@verbatim
<code-snippet name="Create a custom Shopper page with table" lang="php">
// Run: php artisan make:shopper-page Shipping

// app/Livewire/Shopper/Shipping.php
namespace App\Livewire\Shopper;

use App\Models\ShippingMethod;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Shopper\Livewire\Pages\AbstractPageComponent;

class Shipping extends AbstractPageComponent implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public function mount(): void
    {
        $this->authorize('browse_shipping'); // Optional authorization
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(ShippingMethod::query())
            ->columns([
                TextColumn::make('name')
                    ->label(__('Name'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('price')
                    ->label(__('Price'))
                    ->money('USD'),
                TextColumn::make('is_enabled')
                    ->label(__('Status'))
                    ->badge()
                    ->color(fn (bool $state): string => $state ? 'success' : 'gray'),
            ]);
    }

    public function render(): View
    {
        return view('livewire.shopper.shipping');
    }
}
</code-snippet>
@endverbatim

@verbatim
<code-snippet name="Blade view for custom page" lang="blade">
{{-- resources/views/livewire/shopper/shipping.blade.php --}}
<x-shopper::container>
    <x-shopper::breadcrumb :back="route('shopper.settings.index')" :current="__('Shipping Methods')">
        <x-untitledui-chevron-left class="size-4 shrink-0 text-gray-300 dark:text-gray-600" />
        <x-shopper::breadcrumb.link :link="route('shopper.settings.index')" :title="__('Settings')" />
    </x-shopper::breadcrumb>

    <x-shopper::heading class="my-6" :title="__('Shipping Methods')" />

    <x-shopper::card class="mt-5">
        {{ $this->table }}
    </x-shopper::card>
</x-shopper::container>
</code-snippet>
@endverbatim

### Registering Custom Routes

After creating a page, register its route in `routes/shopper.php`:

@verbatim
<code-snippet name="Register custom page route" lang="php">
// config/shopper/routes.php
return [
    'custom_file' => base_path('routes/shopper.php'),
];

// routes/shopper.php
use App\Livewire\Shopper\Shipping;
use Illuminate\Support\Facades\Route;

Route::get('shipping', Shipping::class)->name('shopper.shipping.index');
</code-snippet>
@endverbatim

### Sidebar Navigation System

Shopper uses a sidebar system with 4 default groups. Each group is a class extending `AbstractAdminSidebar`:

- `DashboardSidebar` - Dashboard menu (weight: 1, no heading)
- `CatalogSidebar` - Products, Categories, Collections, Brands (weight: 2)
- `SalesSidebar` - Orders, Discounts (weight: 3)
- `CustomerSidebar` - Customers, Reviews (weight: 4)

Groups with the same name are automatically merged. Groups without a name (empty string or omitted) are merged with the Dashboard group.

To add items to an existing sidebar group or create a new one, create a sidebar extender class:

@verbatim
<code-snippet name="Create sidebar extender to add menu item" lang="php">
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
        // Add to existing "Catalog" group
        $menu->group(__('shopper::layout.sidebar.catalog'), function (Group $group): void {
            $group->weight(2); // Same weight as CatalogSidebar to merge

            $group->item(__('Shipping Methods'), function (Item $item): void {
                $item->weight(5); // Position after other items
                $item->setItemClass('sh-sidebar-item group');
                $item->setActiveClass('sh-sidebar-item-active');
                $item->useSpa(); // Enable SPA navigation
                $item->route('shopper.shipping.index');
                $item->setIcon(
                    icon: 'untitledui-truck-01',
                    iconClass: 'size-5 ' . ($item->isActive() ? 'text-primary-600' : 'text-gray-400 dark:text-gray-500'),
                    attributes: ['stroke-width' => '1.5'],
                );
            });
        });

        return $menu;
    }
}
</code-snippet>
@endverbatim

@verbatim
<code-snippet name="Register sidebar extender in ServiceProvider" lang="php">
// app/Providers/AppServiceProvider.php
namespace App\Providers;

use App\Sidebar\ShippingSidebar;
use Illuminate\Support\ServiceProvider;
use Shopper\Sidebar\SidebarBuilder;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Register the sidebar extender
        $this->app['events']->listen(SidebarBuilder::class, ShippingSidebar::class);
    }
}
</code-snippet>
@endverbatim

### Creating a New Sidebar Group

To create a completely new sidebar group instead of adding to an existing one:

@verbatim
<code-snippet name="Create new sidebar group" lang="php">
// app/Sidebar/CustomSidebar.php
namespace App\Sidebar;

use Shopper\Sidebar\AbstractAdminSidebar;
use Shopper\Sidebar\Contracts\Builder\Group;
use Shopper\Sidebar\Contracts\Builder\Item;
use Shopper\Sidebar\Contracts\Builder\Menu;

class CustomSidebar extends AbstractAdminSidebar
{
    public function extendWith(Menu $menu): Menu
    {
        $menu->group(__('Logistics'), function (Group $group): void {
            $group->weight(5); // After CustomerSidebar (weight 4)
            $group->setAuthorized();

            $group->item(__('Shipping'), function (Item $item): void {
                $item->weight(1);
                $item->setAuthorized($this->user->hasPermissionTo('browse_shipping'));
                $item->useSpa();
                $item->route('shopper.shipping.index');
                $item->setIcon('untitledui-truck-01');
            });

            $group->item(__('Carriers'), function (Item $item): void {
                $item->weight(2);
                $item->useSpa();
                $item->route('shopper.carriers.index');
                $item->setIcon('untitledui-plane');
            });
        });

        return $menu;
    }
}
</code-snippet>
@endverbatim

### Adding Items to the Dashboard Group (No Heading)

To add items alongside the Dashboard (without a group heading), omit the group name:

@verbatim
<code-snippet name="Add item to dashboard group" lang="php">
$menu->group(function (Group $group): void {
    $group->weight(1);
    $group->setAuthorized();

    $group->item(__('Analytics'), function (Item $item): void {
        $item->weight(2); // After Dashboard (weight 1)
        $item->useSpa();
        $item->route('shopper.analytics.index');
        $item->setIcon(
            icon: 'phosphor-chart-line',
            iconClass: 'size-5 ' . ($item->isActive() ? 'text-primary-500' : 'text-gray-400 dark:text-gray-500'),
        );
    });
});
</code-snippet>
@endverbatim

### Sidebar Item Options

When configuring sidebar items, use these methods:

- `$item->weight(int)` - Position in group (lower = higher)
- `$item->setAuthorized(bool)` - Show/hide based on condition (use `$this->user->hasPermissionTo()`)
- `$item->useSpa()` - Enable SPA navigation with `wire:navigate`
- `$item->route('route.name')` - Set the route
- `$item->setIcon(icon, iconClass, attributes)` - Configure icon (use Untitled UI icons: `untitledui-*`)
- `$item->setItemClass()`, `$item->setActiveClass()` - CSS classes
- `$item->item()` - Add nested sub-items

### Model Architecture

All models use contracts and can be resolved from the container. Always use contracts when type-hinting:

@verbatim
<code-snippet name="Resolve Shopper models via contracts" lang="php">
use Shopper\Core\Models\Contracts\Product as ProductContract;
use Shopper\Core\Models\Contracts\Order as OrderContract;
use Shopper\Core\Models\Contracts\Category as CategoryContract;

// In a controller or service
public function __construct(
    private ProductContract $productModel,
) {}

// Query products
$products = resolve(ProductContract::class)::query()
    ->where('is_visible', true)
    ->get();
</code-snippet>
@endverbatim

### Custom Models

To extend Shopper models, create your own model extending the base and update `config/shopper/models.php`:

@verbatim
<code-snippet name="Create and register custom model" lang="php">
// app/Models/Product.php
namespace App\Models;

use Shopper\Core\Models\Product as ShopperProduct;

class Product extends ShopperProduct
{
    public function customRelation()
    {
        return $this->hasMany(CustomModel::class);
    }
}

// config/shopper/models.php
return [
    'product' => App\Models\Product::class,
];
</code-snippet>
@endverbatim

### Product Types

Products have types that determine capabilities. Use the `ProductType` enum:

- `ProductType::Standard` - Physical products with shipping, supports variants
- `ProductType::Variant` - Product with variants (sizes, colors)
- `ProductType::Virtual` - Digital products, no shipping, no variants
- `ProductType::External` - Affiliate products, no shipping, no variants

Check capabilities with: `$product->canUseVariants()`, `$product->canUseShipping()`, `$product->isVirtual()`

### Stock Management

Products and variants use the `HasStock` trait. Shopper supports multi-location inventory:

@verbatim
<code-snippet name="Manage inventory stock" lang="php">
use Shopper\Core\Models\Product;
use Shopper\Core\Models\Inventory;

$product = Product::find($id);
$inventory = Inventory::where('is_default', true)->first();

$product->setStock(100, $inventory->id);
$product->decreaseStock($inventory->id, 5);
$currentStock = $product->getStock();
</code-snippet>
@endverbatim

### Pricing

Products support multi-currency pricing. Amounts are stored in cents:

@verbatim
<code-snippet name="Create product price" lang="php">
use Shopper\Core\Models\Currency;

$product->prices()->create([
    'currency_id' => Currency::where('code', 'USD')->first()->id,
    'amount' => 2999,         // $29.99
    'compare_amount' => 3999, // $39.99 (crossed-out price)
    'cost_amount' => 1500,    // $15.00 (cost for profit calc)
]);
</code-snippet>
@endverbatim

### Categories

Categories support hierarchical structures using LaravelAdjacencyList:

@verbatim
<code-snippet name="Work with category hierarchy" lang="php">
$category->children;              // Direct children
$category->descendantCategories(); // All descendants
$category->parent;                // Parent category
$category->ancestors;             // All ancestors
</code-snippet>
@endverbatim

### Orders

Orders track purchases with statuses. Use the `OrderStatus` enum:

- `OrderStatus::Pending`, `OrderStatus::Register`, `OrderStatus::Paid`
- `OrderStatus::Shipped`, `OrderStatus::Completed`, `OrderStatus::Cancelled`

@verbatim
<code-snippet name="Query orders with relationships" lang="php">
use Shopper\Core\Models\Order;
use Shopper\Core\Enum\OrderStatus;

$orders = Order::with(['items', 'customer', 'shippingAddress', 'zone'])
    ->where('status', OrderStatus::Pending)
    ->get();
</code-snippet>
@endverbatim

### Events

Shopper dispatches events for major actions. Listen to these for custom logic:

- Products: `ProductCreated`, `ProductUpdated`, `ProductDeleted`
- Orders: `OrderCreated`, `OrderCompleted`, `OrderPaid`, `OrderCancel`, `OrderArchived`

@verbatim
<code-snippet name="Listen to Shopper events" lang="php">
use Shopper\Core\Events\Products\ProductCreated;
use Shopper\Core\Events\Orders\OrderCreated;

// In EventServiceProvider
protected $listen = [
    ProductCreated::class => [YourListener::class],
    OrderCreated::class => [SendOrderConfirmation::class],
];
</code-snippet>
@endverbatim

### Extending Navigation (Simple Method)

For quick sidebar customization, use a closure in your ServiceProvider:

@verbatim
<code-snippet name="Add sidebar item with closure" lang="php">
// app/Providers/AppServiceProvider.php
use Illuminate\Support\Facades\Event;
use Shopper\Sidebar\Contracts\Builder\Group;
use Shopper\Sidebar\Contracts\Builder\Item;
use Shopper\Sidebar\SidebarBuilder;

public function boot(): void
{
    Event::listen(SidebarBuilder::class, function (SidebarBuilder $sidebar) {
        $sidebar->add(
            $sidebar->getMenu()->group('Custom Section', function (Group $group) {
                $group->weight(50);
                $group->setAuthorized();
                $group->setGroupItemsClass('space-y-1');
                $group->setHeadingClass('sh-heading');

                $group->item('My Custom Page', function (Item $item) {
                    $item->weight(1);
                    $item->setItemClass('sh-sidebar-item group');
                    $item->setActiveClass('sh-sidebar-item-active');
                    $item->useSpa();
                    $item->route('shopper.custom.index');
                    $item->setIcon(
                        icon: 'heroicon-o-star',
                        iconClass: 'size-5 ' . ($item->isActive() ? 'text-primary-600' : 'text-gray-400 dark:text-gray-500'),
                    );
                });
            })
        );
    });
}
</code-snippet>
@endverbatim

### Override Existing Livewire Components

Shopper components can be overridden via config files in `config/shopper/components/`. Available config files:

- `account.php` - Account/profile components
- `brand.php` - Brand management
- `category.php` - Category management
- `collection.php` - Collection management
- `customer.php` - Customer management
- `dashboard.php` - Dashboard components
- `discount.php` - Discount management
- `order.php` - Order management
- `product.php` - Product management (pages, forms, modals, slide-overs)
- `review.php` - Review management
- `setting.php` - Settings pages and components

Each config file has two sections: `pages` (full page components) and `components` (partial components like forms, modals, slide-overs).

@verbatim
<code-snippet name="Override existing component" lang="php">
// Publish the config file first
// php artisan vendor:publish --tag=shopper-config

// config/shopper/components/product.php
return [
    'pages' => [
        'product-index' => App\Livewire\Shopper\Products\Index::class, // Override index page
        'product-edit' => \Shopper\Livewire\Pages\Product\Edit::class, // Keep default
        'variant-edit' => \Shopper\Livewire\Pages\Product\Variant::class,
        'attribute-index' => \Shopper\Livewire\Pages\Attribute\Browse::class,
    ],

    'components' => [
        'products.form.edit' => App\Livewire\Shopper\Products\EditForm::class, // Override form
        'products.form.media' => \Shopper\Livewire\Components\Products\Form\Media::class,
        // ... keep other defaults
    ],
];
</code-snippet>
@endverbatim

@verbatim
<code-snippet name="Create custom component extending base" lang="php">
// app/Livewire/Shopper/Products/Index.php
namespace App\Livewire\Shopper\Products;

use Filament\Tables\Table;
use Shopper\Livewire\Pages\Product\Index as BaseIndex;

class Index extends BaseIndex
{
    public function table(Table $table): Table
    {
        return parent::table($table)
            ->columns([
                // Add or modify columns
                ...parent::table($table)->getColumns(),
                TextColumn::make('custom_field'),
            ])
            ->filters([
                // Add custom filters
            ]);
    }
}
</code-snippet>
@endverbatim

### Permissions

Shopper uses Spatie Laravel Permission. Check permissions in Livewire components:

@verbatim
<code-snippet name="Authorization in components" lang="php">
// In Livewire component
public function mount(): void
{
    $this->authorize('browse_products');
}

// In Blade
@can('add_products')
    <x-filament::button>Add Product</x-filament::button>
@endcan
</code-snippet>
@endverbatim

### Helper Functions

- `shopper_table('products')` - Returns prefixed table name (e.g., `sh_products`)
- `generate_number()` - Generates order number with configured prefix
- `shopper_fallback_url()` - Returns fallback image URL
- `shopper_setting('shop_name')` - Gets shop setting value
- `shopper()->auth()->user()` - Gets authenticated admin user

### Feature Flags

Enable/disable features in `config/shopper/features.php`:

@verbatim
<code-snippet name="Check feature flag" lang="php">
if (\Shopper\Feature::enabled('review')) {
    // Show reviews functionality
}
</code-snippet>
@endverbatim

### Artisan Commands

- `{{ $assist->artisanCommand('shopper:install') }}` - Install Shopper
- `{{ $assist->artisanCommand('shopper:user') }}` - Create admin user
- `{{ $assist->artisanCommand('shopper:publish') }}` - Publish assets and config
- `{{ $assist->artisanCommand('shopper:link') }}` - Create storage symlink
- `{{ $assist->artisanCommand('make:shopper-page {PageName}') }}` - Create custom admin page
- `{{ $assist->artisanCommand('shopper:component:publish') }}` - Publish specific components
- `{{ $assist->artisanCommand('shopper:starter-kit:install') }}` - Install frontend starter kit

### Media Management

Products use Spatie Media Library. Collections are configured in `config/shopper/media.php`:

@verbatim
<code-snippet name="Add media to product" lang="php">
// Add thumbnail
$product->addMedia($file)
    ->toMediaCollection(config('shopper.media.storage.thumbnail_collection'));

// Add gallery images
$product->addMedia($file)
    ->toMediaCollection(config('shopper.media.storage.collection_name'));

// Get URLs
$thumbnail = $product->getFirstMediaUrl(config('shopper.media.storage.thumbnail_collection'));
</code-snippet>
@endverbatim

### Shopper Blade Components

Use these Shopper Blade components in your custom pages:

@verbatim
- `<x-shopper::container>` - Main content container
- `<x-shopper::card>` - Card wrapper
- `<x-shopper::heading :title="$title">` - Page heading with optional action slot
- `<x-shopper::breadcrumb>` - Breadcrumb navigation
- `<x-filament::button>` - Primary button
- `<x-filament::button color="gray">` - Gray/default button
- `<x-shopper::empty-card>` - Empty state card
- `<x-shopper::separator>` - Section separator
@endverbatim

### Database Tables

All Shopper tables use a configurable prefix (default: `sh_`). Main tables:

- `sh_products`, `sh_product_variants` - Products and variants
- `sh_orders`, `sh_order_items` - Orders and line items
- `sh_categories`, `sh_brands`, `sh_collections` - Catalog organization
- `sh_customers`, `sh_addresses` - Customer data
- `sh_inventories`, `sh_inventory_histories` - Stock management
- `sh_discounts` - Discount codes and rules

### Testing

When testing Shopper functionality, use factories and respect the model contracts:

@verbatim
<code-snippet name="Testing with Shopper models" lang="php">
use Shopper\Core\Models\Product;

it('can create a product', function () {
    $product = Product::factory()->create([
        'name' => 'Test Product',
        'is_visible' => true,
    ]);

    expect($product)->toBeInstanceOf(Product::class)
        ->and($product->is_visible)->toBeTrue();
});
</code-snippet>
@endverbatim
