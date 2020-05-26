<?php

namespace Shopper\Framework\Tests;

use Livewire\LivewireServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;
use Shopper\Framework\FrameworkServiceProvider;

abstract class TestCase extends Orchestra
{
    public function setUp(): void
    {
        parent::setUp();

        $this->setUpDatabase();
    }

    protected function getPackageProviders($app)
    {
        return [
            LivewireServiceProvider::class,
            FrameworkServiceProvider::class,
        ];
    }

    protected function setUpDatabase()
    {

    }
}
