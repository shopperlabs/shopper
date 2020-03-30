<?php

namespace Shopper\Framework\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Livewire\Livewire;
use Shopper\Framework\Http\Components\Blade\Breadcrumb;
use Shopper\Framework\Http\Components\Blade\DeleteAction;
use Shopper\Framework\Http\Components\Livewire\BrandList;
use Shopper\Framework\Http\Components\Livewire\CategoryList;
use Shopper\Framework\Http\Components\Livewire\CollectionList;
use Shopper\Framework\Http\Components\Livewire\DateTimePicker;

class ComponentsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        /**
         * Livewire Components.
         */
        Livewire::component('category-list', CategoryList::class);
        Livewire::component('brand-list', BrandList::class);
        Livewire::component('collection-list', CollectionList::class);

        /**
         * Blade Components.
         */
        Blade::component('datetime-picker', DateTimePicker::class);
        Blade::component('breadcrumb', Breadcrumb::class);
        Blade::component('delete-action', DeleteAction::class);
    }
}
