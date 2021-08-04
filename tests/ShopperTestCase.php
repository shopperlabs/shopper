<?php

namespace Shopper\Framework\Tests;

use Mockery;
use Orchestra\Testbench\TestCase;
use Livewire\LivewireServiceProvider;
use Shopper\Framework\Models\User\User;
use Shopper\Framework\FrameworkServiceProvider;

class ShopperTestCase extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function tearDown(): void
    {
        Mockery::close();
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['migrator']->path(__DIR__ . '/../database/migrations');

        $app['config']->set('database.default', 'sqlite');

        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        // Use test User model for users provider
        $app['config']->set('auth.providers.users.model', User::class);
    }

    protected function getPackageProviders($app)
    {
        return [LivewireServiceProvider::class, FrameworkServiceProvider::class];
    }
}
