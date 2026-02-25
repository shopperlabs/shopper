<?php

declare(strict_types=1);

namespace Shopper\Cart;

use Shopper\Cart\Console\PruneCartsCommand;
use Shopper\Cart\Discounts\DiscountCalculator;
use Shopper\Cart\Discounts\DiscountValidator;
use Shopper\Cart\Models\Cart;
use Shopper\Cart\Models\CartLine;
use Shopper\Cart\Pipelines\CartPipelineRunner;
use Shopper\Core\Models\Contracts\Cart as CartContract;
use Shopper\Core\Models\Contracts\CartLine as CartLineContract;
use Shopper\Core\Traits\HasRegisterConfigAndMigrationFiles;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

final class CartServiceProvider extends PackageServiceProvider
{
    use HasRegisterConfigAndMigrationFiles;

    /** @var string[] */
    protected array $configFiles = [
        'cart',
    ];

    protected string $root = __DIR__.'/..';

    public function configurePackage(Package $package): void
    {
        $package->name('shopper-cart')
            ->hasTranslations()
            ->hasCommand(PruneCartsCommand::class);
    }

    public function packageRegistered(): void
    {
        $this->registerConfigFiles();
        $this->registerDatabase();
        $this->registerModelBindings();

        $this->app->singleton(CartPipelineRunner::class);
        $this->app->singleton(DiscountValidator::class);
        $this->app->singleton(DiscountCalculator::class);
        $this->app->singleton(CartManager::class);
        $this->app->singleton(CartSessionManager::class);
    }

    protected function registerModelBindings(): void
    {
        $this->app->bind(CartContract::class, config('shopper.cart.models.cart', Cart::class));
        $this->app->bind(CartLineContract::class, config('shopper.cart.models.cart_line', CartLine::class));
    }
}
