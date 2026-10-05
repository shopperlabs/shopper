<?php

declare(strict_types=1);

namespace Shopper\Api;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Contracts\Foundation\CachesConfiguration;
use Illuminate\Support\Facades\Event;
use Shopper\Core\Events\Payments\PaymentOrphaned;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

final class ApiServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('shopper-api')
            ->hasTranslations()
            ->hasRoutes(['store'])
            ->hasCommands([
                Console\InstallCommand::class,
            ]);
    }

    public function packageRegistered(): void
    {
        $this->mergeApiConfig();

        $this->app->singleton(Support\ResourceManifest::class);
    }

    public function packageBooted(): void
    {
        $this->publishes(
            [__DIR__.'/../config/api.php' => config_path('shopper/api.php')],
            'shopper-config',
        );

        $this->scheduleTokenPruning();

        Event::listen(PaymentOrphaned::class, Listeners\CompleteOrphanedCart::class);
    }

    /**
     * Laravel merges a published config file on its top level only, which
     * would hide every resource or key added to the resources registry after
     * the file was published. The registry is merged one level deeper, a
     * published resource keeping authority over the keys it declares.
     */
    private function mergeApiConfig(): void
    {
        if ($this->app instanceof CachesConfiguration && $this->app->configurationIsCached()) {
            return;
        }

        /** @var array{resources: array<string, mixed>} $defaults */
        $defaults = require __DIR__.'/../config/api.php';

        /** @var array<string, mixed> $published */
        $published = (array) config('shopper.api', []);

        /** @var array<string, mixed> $resources */
        $resources = (array) ($published['resources'] ?? []);

        foreach ($defaults['resources'] as $resource => $default) {
            $resources[$resource] = is_array($default)
                ? array_merge($default, (array) ($resources[$resource] ?? []))
                : $resources[$resource] ?? $default;
        }

        config(['shopper.api' => array_merge($defaults, $published, ['resources' => $resources])]);
    }

    private function scheduleTokenPruning(): void
    {
        $this->callAfterResolving(Schedule::class, function (Schedule $schedule): void {
            $schedule->command('sanctum:prune-expired', ['--hours' => 24])->daily();
        });
    }
}
