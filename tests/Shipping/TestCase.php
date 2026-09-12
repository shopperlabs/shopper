<?php

declare(strict_types=1);

namespace Tests\Shipping;

use Illuminate\Support\Facades\Http;
use Livewire\LivewireServiceProvider;
use Shopper\Core\CoreServiceProvider;
use Shopper\FedEx\FedExServiceProvider;
use Shopper\Shipping\ShippingServiceProvider;
use Shopper\ShopperServiceProvider;
use Shopper\Sidebar\SidebarServiceProvider;
use Shopper\Ups\UpsServiceProvider;
use Shopper\Usps\UspsServiceProvider;
use Spatie\MediaLibrary\MediaLibraryServiceProvider;
use Spatie\Permission\PermissionRegistrar;
use Spatie\Permission\PermissionServiceProvider;

abstract class TestCase extends \Tests\TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->app->make(PermissionRegistrar::class)->forgetCachedPermissions();

        Http::preventStrayRequests();
    }

    protected function defineEnvironment($app): void
    {
        parent::defineEnvironment($app);

        $app['config']->set('cache.default', 'array');
    }

    protected function getPackageProviders($app): array
    {
        return [
            LivewireServiceProvider::class,
            CoreServiceProvider::class,
            ShopperServiceProvider::class,
            SidebarServiceProvider::class,
            MediaLibraryServiceProvider::class,
            PermissionServiceProvider::class,
            ShippingServiceProvider::class,
            UpsServiceProvider::class,
            FedExServiceProvider::class,
            UspsServiceProvider::class,
        ];
    }
}
