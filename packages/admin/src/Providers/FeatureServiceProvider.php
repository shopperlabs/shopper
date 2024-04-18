<?php

declare(strict_types=1);

namespace Shopper\Providers;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use Shopper\Traits\LoadComponents;

final class FeatureServiceProvider extends ServiceProvider
{
    use LoadComponents;

    protected array $componentsConfig = [
        'brand',
        'category',
        'collection',
        'customer',
        'discount',
        'product',
        'review',
    ];

    protected string $root = __DIR__ . '/../..';

    public function register(): void
    {
        $this->registerComponentsConfigFiles();
    }

    public function boot(): void
    {
        foreach ($this->components() as $alias => $component) {
            Livewire::component("shopper-{$alias}", $component);
        }
    }

    protected function components(): array
    {
        $components = [];

        foreach ($this->componentsConfig as $config) {
            $components = array_merge($this->loadLivewireComponents($config), $components);
        }

        return $components;
    }
}
