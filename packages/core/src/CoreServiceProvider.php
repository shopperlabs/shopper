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

    protected string $root = __DIR__.'/..';

    public function boot(): void
    {
        setlocale(LC_TIME, config('shopper.core.locale'));
        Carbon::setLocale(config('shopper.core.locale'));
    }

    public function register(): void
    {
        $this->registerConfigFiles();
        $this->registerDatabase();

        $this->app->register(SidebarServiceProvider::class);
        $this->app->singleton('shopper', fn () => new Shopper());
    }
}
