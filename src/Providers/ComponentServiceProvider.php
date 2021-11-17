<?php

namespace Shopper\Framework\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\Compilers\BladeCompiler;
use Livewire\Component;
use Livewire\Livewire;

class ComponentServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        $this->registerLivewireComponents();
        $this->registerBladeComponents();

        Component::macro('notify', function ($params) {
            $this->dispatchBrowserEvent('notify', $params);
        });
    }

    /**
     * Register customs Blade Components.
     */
    public function registerBladeComponents()
    {
        $this->callAfterResolving(BladeCompiler::class, function () {
            $this->registerComponent('alert');
            $this->registerComponent('application-icon');
            $this->registerComponent('application-logo');
            $this->registerComponent('button');
            $this->registerComponent('breadcrumb');
            $this->registerComponent('breadcrumb-link');
            $this->registerComponent('confirm-modal');
            $this->registerComponent('confirms-password');
            $this->registerComponent('danger-button');
            $this->registerComponent('default-button');
            $this->registerComponent('delete-action');
            $this->registerComponent('dialog-modal');
            $this->registerComponent('dropdown');
            $this->registerComponent('dropdown-button');
            $this->registerComponent('dropdown-link');
            $this->registerComponent('empty-state');
            $this->registerComponent('heading');
            $this->registerComponent('input.avatar-upload');
            $this->registerComponent('input.checkbox');
            $this->registerComponent('input.color-picker');
            $this->registerComponent('input.filepond');
            $this->registerComponent('input.group');
            $this->registerComponent('input.markdown');
            $this->registerComponent('input.multiple-upload');
            $this->registerComponent('input.radio');
            $this->registerComponent('input.search');
            $this->registerComponent('input.select');
            $this->registerComponent('input.single-upload');
            $this->registerComponent('input.text');
            $this->registerComponent('input.textarea');
            $this->registerComponent('label');
            $this->registerComponent('learn-more');
            $this->registerComponent('loader');
            $this->registerComponent('menu-item');
            $this->registerComponent('modal');
            $this->registerComponent('notify');
            $this->registerComponent('tables.table-head');
            $this->registerComponent('tables.table-td');
            $this->registerComponent('time');
            $this->registerComponent('validation-errors');
            $this->registerComponent('wip');
            $this->registerComponent('wip-placeholder');
        });

        $prefix = config('shopper.components.prefix', 'shopper');

        $this->callAfterResolving(BladeCompiler::class, function (BladeCompiler $blade) use ($prefix) {
            foreach (config('shopper.components.blade', []) as $component) {
                $blade->component($component['class'], $component['alias'], $prefix);
            }
        });
    }

    /**
     * Register Livewire components.
     */
    public function registerLivewireComponents()
    {
        $prefix = config('shopper.components.prefix', 'shopper');

        foreach (config('shopper.components.livewire', []) as $alias => $component) {
            $alias = $prefix ? "$prefix-$alias" : $alias;

            Livewire::component($alias, $component);
        }
    }

    /**
     * Register the given component.
     */
    protected function registerComponent(string $component)
    {
        $prefix = config('shopper.components.prefix', 'shopper');

        Blade::component('shopper::components.' . $component, $prefix . '-' . $component);
    }
}
