<?php

declare(strict_types=1);

namespace Shopper\Cart;

use Illuminate\Console\Scheduling\Schedule;
use Shopper\Cart\Console\PruneCartsCommand;
use Shopper\Cart\Discounts\DiscountEligibilityManager;
use Shopper\Cart\Discounts\DiscountValidator;
use Shopper\Cart\Discounts\Eligibility\CustomersEligibilityRule;
use Shopper\Cart\Discounts\Eligibility\EveryoneEligibilityRule;
use Shopper\Cart\Discounts\PromotionResolver;
use Shopper\Cart\Events\CartCompleted;
use Shopper\Cart\Models\Cart;
use Shopper\Cart\Models\CartLine;
use Shopper\Cart\Models\Contracts\Cart as CartContract;
use Shopper\Cart\Models\Contracts\CartLine as CartLineContract;
use Shopper\Cart\Pipelines\CartPipeline;
use Shopper\Cart\Pipelines\CartPipelineRunner;
use Shopper\Core\Enum\WebhookEventType;
use Shopper\Core\Traits\HasRegisterConfigAndMigrationFiles;
use Shopper\Core\Webhooks\Facades\Webhooks;
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

    public function packageBooted(): void
    {
        $this->callAfterResolving(Schedule::class, function (Schedule $schedule): void {
            $schedule->command('shopper:prune-carts')->daily();
        });

        Webhooks::register(CartCompleted::class, WebhookEventType::CartCompleted->value);
    }

    public function packageRegistered(): void
    {
        $this->registerConfigFiles();
        $this->registerDatabase();
        $this->registerModelBindings();

        $this->app->singleton(CartPipeline::class);
        $this->app->singleton(CartPipelineRunner::class);
        $this->app->singleton(DiscountValidator::class);
        $this->app->singleton(PromotionResolver::class);
        $this->app->singleton(CartManager::class);
        $this->app->singleton(CartSessionManager::class);

        $this->app->singleton(DiscountEligibilityManager::class, function (): DiscountEligibilityManager {
            $manager = new DiscountEligibilityManager;
            $manager->register(new EveryoneEligibilityRule);
            $manager->register(new CustomersEligibilityRule);

            return $manager;
        });
    }

    protected function registerModelBindings(): void
    {
        $this->app->bind(CartContract::class, config('shopper.cart.models.cart', Cart::class));
        $this->app->bind(CartLineContract::class, config('shopper.cart.models.cart_line', CartLine::class));
    }
}
