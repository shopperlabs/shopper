<?php

declare(strict_types=1);

namespace Shopper\Api;

use Illuminate\Console\Scheduling\Schedule;
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
        $this->mergeConfigFrom(__DIR__.'/../config/api.php', 'shopper.api');

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

    private function scheduleTokenPruning(): void
    {
        $this->callAfterResolving(Schedule::class, function (Schedule $schedule): void {
            $schedule->command('sanctum:prune-expired', ['--hours' => 24])->daily();
        });
    }
}
