<?php

namespace Shopper\Framework\Providers;

use Livewire\Component;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\Compilers\BladeCompiler;
use Shopper\Framework\Traits\CanRegisterLivewireComponentDirectories;

class ComponentServiceProvider extends ServiceProvider
{
    use CanRegisterLivewireComponentDirectories;

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
            $this->registerComponent('datetime-picker');
            $this->registerComponent('danger-button');
            $this->registerComponent('default-button');
            $this->registerComponent('delete-action');
            $this->registerComponent('dialog-modal');
            $this->registerComponent('dropdown');
            $this->registerComponent('dropdown-button');
            $this->registerComponent('dropdown-link');
            $this->registerComponent('empty-state');
            $this->registerComponent('heading');
            $this->registerComponent('input.checkbox');
            $this->registerComponent('input.file-upload');
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
            $this->registerComponent('modal');
            $this->registerComponent('notify');
            $this->registerComponent('tables.table-head');
            $this->registerComponent('tables.table-td');
            $this->registerComponent('time');
            $this->registerComponent('validation-errors');
            $this->registerComponent('wip');
            $this->registerComponent('wip-placeholder');
        });
    }

    /**
     * Register Livewire components.
     */
    public function registerLivewireComponents()
    {
        $this->registerLivewireComponentDirectory(
            __DIR__ . '/../Http/Livewire',
            '\\Shopper\\Framework\\Http\\Livewire',
            'shopper'
        );
    }

    /**
     * Register the given component.
     */
    protected function registerComponent(string $component)
    {
        Blade::component('shopper::components.' . $component, 'shopper-' . $component);
    }
}
