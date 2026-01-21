<?php

declare(strict_types=1);

namespace Shopper\Sidebar;

use Illuminate\Foundation\Application;
use Livewire\Livewire;
use Shopper\Sidebar\Contracts\Builder\Append;
use Shopper\Sidebar\Contracts\Builder\Badge;
use Shopper\Sidebar\Contracts\Builder\Group;
use Shopper\Sidebar\Contracts\Builder\Item;
use Shopper\Sidebar\Contracts\Builder\Menu;
use Shopper\Sidebar\Domain\DefaultAppend;
use Shopper\Sidebar\Domain\DefaultBadge;
use Shopper\Sidebar\Domain\DefaultGroup;
use Shopper\Sidebar\Domain\DefaultItem;
use Shopper\Sidebar\Domain\DefaultMenu;
use Shopper\Sidebar\Infrastructure\SidebarFlusher;
use Shopper\Sidebar\Infrastructure\SidebarFlusherFactory;
use Shopper\Sidebar\Infrastructure\SidebarResolver;
use Shopper\Sidebar\Infrastructure\SidebarResolverFactory;
use Shopper\Sidebar\Livewire\SidebarComponent;
use Shopper\Sidebar\Presentation\SidebarRenderer;
use Shopper\Sidebar\Presentation\View\SidebarRenderer as IlluminateSidebarRenderer;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

final class SidebarServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('sidebar')
            ->hasConfigFile()
            ->hasTranslations()
            ->hasViews();
    }

    public function packageBooted(): void
    {
        $this->registerLivewireComponents();
        $this->registerPublishables();
    }

    public function packageRegistered(): void
    {
        $this->app->bind(SidebarResolver::class, function (Application $app) {
            $resolver = SidebarResolverFactory::getClassName(
                name: $app['config']->get('sidebar.cache.method')
            );

            return $app->make($resolver);
        });

        $this->app->bind(SidebarFlusher::class, function (Application $app) {
            $flusher = SidebarFlusherFactory::getClassName(
                name: $app['config']->get('sidebar.cache.method')
            );

            return $app->make($flusher);
        });

        $this->app->singleton(SidebarManager::class);

        $this->bindingSidebarMenu();
    }

    public function bindingSidebarMenu(): void
    {
        $this->app->bind(Menu::class, DefaultMenu::class);
        $this->app->bind(Group::class, DefaultGroup::class);
        $this->app->bind(Item::class, DefaultItem::class);
        $this->app->bind(Badge::class, DefaultBadge::class);
        $this->app->bind(Append::class, DefaultAppend::class);
        $this->app->bind(SidebarRenderer::class, IlluminateSidebarRenderer::class);
    }

    protected function registerLivewireComponents(): void
    {
        if (class_exists(Livewire::class)) {
            Livewire::component('sidebar', SidebarComponent::class);
        }
    }

    protected function registerPublishables(): void
    {
        if ($this->app->runningInConsole()) {
            // Publish compiled JS to public for direct usage
            $this->publishes([
                __DIR__.'/../dist' => public_path('vendor/sidebar'),
            ], 'sidebar-assets');

            // Publish source JS to resources for custom builds
            $this->publishes([
                __DIR__.'/../resources/js' => resource_path('js/vendor/sidebar'),
            ], 'sidebar-js');
        }
    }
}
