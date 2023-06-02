<?php

declare(strict_types=1);

namespace Shopper;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Livewire\Component;
use Livewire\Livewire;
use Shopper\Console;
use Shopper\Core\Events\BuildingSidebar;
use Shopper\Core\Traits\HasRegisterConfigAndMigrationFiles;
use Shopper\Events\DashboardSidebar;
use Shopper\Events\OrderSidebar;
use Shopper\Events\ShopSidebar;
use Shopper\Http\Composers\GlobalComposer;
use Shopper\Http\Composers\SidebarCreator;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class ShopperServiceProvider extends PackageServiceProvider
{
    use HasRegisterConfigAndMigrationFiles;

    protected array $configFiles = [
        'admin',
        'auth',
        'components',
        'models',
        'routes',
        'settings',
    ];

    protected string $root = __DIR__.'/..';

    public function configurePackage(Package $package): void
    {
        $package
            ->name('shopper')
            ->hasViews()
            ->hasTranslations()
            ->hasViewComponents('shopper')
            ->hasRoute('web')
            ->hasCommands([
                Console\InstallCommand::class,
                Console\PublishCommand::class,
                Console\SymlinkCommand::class,
                Console\UserCommand::class,
            ]);
    }

    public function packageBooted(): void
    {
        $this->bootLivewireComponents();
        $this->bootModelRelationName();

        Builder::macro(
            'search',
            fn ($field, $string) => $string
                ? $this->where($field, 'like', '%' . $string . '%')
                : $this
        );
    }

    public function packageRegistered(): void
    {
        $this->registerConfigFiles();
        $this->registerDatabase();
        $this->registerViewsComposer();

        $this->app['events']->listen(BuildingSidebar::class, DashboardSidebar::class);
        $this->app['events']->listen(BuildingSidebar::class, ShopSidebar::class);
        $this->app['events']->listen(BuildingSidebar::class, OrderSidebar::class);
    }

    public function bootModelRelationName(): void
    {
        Relation::morphMap([
            'brand' => config('shopper.models.brand'),
            'category' => config('shopper.models.category'),
            'collection' => config('shopper.models.collection'),
            'product' => config('shopper.models.product'),
            'channel' => config('shopper.models.products'),
        ]);
    }

    public function bootLivewireComponents(): void
    {
        $prefix = config('shopper.components.prefix', 'shopper');

        Livewire::listen('component.hydrate', function ($component) {
            $this->app->singleton(Component::class, fn () => $component);
        });

        foreach (config('shopper.components.livewire', []) as $alias => $component) {
            $alias = $prefix ? "$prefix-$alias" : $alias;

            Livewire::component($alias, $component);
        }
    }

    public function registerViewsComposer(): void
    {
        view()->composer('*', GlobalComposer::class);
        view()->creator('shopper::components.layouts.app.sidebar.secondary', SidebarCreator::class);
    }
}
