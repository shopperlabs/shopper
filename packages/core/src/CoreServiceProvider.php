<?php

declare(strict_types=1);

namespace Shopper\Core;

use Carbon\Carbon;
use Illuminate\Support\ServiceProvider;
use Shopper\Core\Traits\HasRegisterConfigAndMigrationFiles;

class CoreServiceProvider extends ServiceProvider
{
    use HasRegisterConfigAndMigrationFiles;

    protected array $configFiles = [
        'core',
        'media',
    ];

    public function boot(): void
    {
        setlocale(LC_TIME, config('shopper.core.locale'));
        Carbon::setLocale(config('shopper.core.locale'));

        $this->app->register(SidebarServiceProvider::class);
    }

    public function register(): void
    {
        $this->app->singleton('shopper', fn () => new Shopper());
    }
}
