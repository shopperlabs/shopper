<?php

declare(strict_types=1);

namespace Shopper\Core;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\Relation;
use Shopper\Core\Console\InstallCommand;
use Shopper\Core\Console\SyncCollectionsCommand;
use Shopper\Core\Models\Address;
use Shopper\Core\Models\Attribute;
use Shopper\Core\Models\Category;
use Shopper\Core\Models\Channel;
use Shopper\Core\Models\Inventory;
use Shopper\Core\Models\Order;
use Shopper\Core\Models\Product;
use Shopper\Core\Models\ProductVariant;
use Shopper\Core\Observers\AddressObserver;
use Shopper\Core\Observers\AttributeObserver;
use Shopper\Core\Observers\CategoryObserver;
use Shopper\Core\Observers\ChannelObserver;
use Shopper\Core\Observers\InventoryObserver;
use Shopper\Core\Observers\OrderObserver;
use Shopper\Core\Observers\ProductObserver;
use Shopper\Core\Observers\ProductVariantObserver;
use Shopper\Core\Traits\HasRegisterConfigAndMigrationFiles;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

final class CoreServiceProvider extends PackageServiceProvider
{
    use HasRegisterConfigAndMigrationFiles;

    /** @var string[] */
    protected array $configFiles = [
        'core',
        'media',
        'models',
        'orders',
    ];

    protected string $root = __DIR__.'/..';

    public function configurePackage(Package $package): void
    {
        $package
            ->name('shopper-core')
            ->hasTranslations()
            ->hasCommands([
                InstallCommand::class,
                SyncCollectionsCommand::class,
            ]);
    }

    public function packageBooted(): void
    {
        setlocale(LC_ALL, config('app.locale'));

        Carbon::setLocale(config('app.locale'));

        $this->bootModelRelationName();
        $this->registerObservers();
    }

    public function packageRegistered(): void
    {
        $this->registerConfigFiles();
        $this->registerDatabase();
        $this->registerModelBindings();
    }

    protected function registerObservers(): void
    {
        Address::observeUsingConfiguredClass(AddressObserver::class);
        Category::observeUsingConfiguredClass(CategoryObserver::class);
        Channel::observeUsingConfiguredClass(ChannelObserver::class);
        Inventory::observeUsingConfiguredClass(InventoryObserver::class);
        Order::observeUsingConfiguredClass(OrderObserver::class);
        Product::observeUsingConfiguredClass(ProductObserver::class);
        ProductVariant::observeUsingConfiguredClass(ProductVariantObserver::class);

        Attribute::observe(AttributeObserver::class);
    }

    protected function registerModelBindings(): void
    {
        $models = [
            'address' => Models\Contracts\Address::class,
            'brand' => Models\Contracts\Brand::class,
            'category' => Models\Contracts\Category::class,
            'collection' => Models\Contracts\Collection::class,
            'product' => Models\Contracts\Product::class,
            'variant' => Models\Contracts\ProductVariant::class,
            'channel' => Models\Contracts\Channel::class,
            'order' => Models\Contracts\Order::class,
            'inventory' => Models\Contracts\Inventory::class,
            'supplier' => Models\Contracts\Supplier::class,
        ];

        foreach ($models as $configKey => $contract) {
            $this->app->bind($contract, config("shopper.models.{$configKey}"));
        }
    }

    protected function bootModelRelationName(): void
    {
        Relation::morphMap([
            'address' => config('shopper.models.address'),
            'brand' => config('shopper.models.brand'),
            'category' => config('shopper.models.category'),
            'collection' => config('shopper.models.collection'),
            'product' => config('shopper.models.product'),
            'variant' => config('shopper.models.variant'),
            'channel' => config('shopper.models.channel'),
            'order' => config('shopper.models.order'),
            'inventory' => config('shopper.models.inventory'),
            'supplier' => config('shopper.models.supplier'),
        ]);
    }
}
