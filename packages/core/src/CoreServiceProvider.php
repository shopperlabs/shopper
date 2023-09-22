<?php

declare(strict_types=1);

namespace Shopper\Core;

use Carbon\Carbon;
use Illuminate\Support\ServiceProvider;
use Shopper\Core\Traits\HasRegisterConfigAndMigrationFiles;

final class CoreServiceProvider extends ServiceProvider
{
    use HasRegisterConfigAndMigrationFiles;

    protected array $configFiles = [
        'core',
        'media',
    ];

    protected string $root = __DIR__ . '/..';

    public function boot(): void
    {
        setlocale(LC_ALL, config('app.locale'));
        Carbon::setLocale(config('app.locale'));
    }

    public function register(): void
    {
        $this->registerConfigFiles();
        $this->registerDatabase();
    }
}
