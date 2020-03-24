<?php

namespace Shopper\Framework\Providers;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use Shopper\Framework\Http\Components\BrandList;
use Shopper\Framework\Http\Components\CategoryList;

class LivewireServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Livewire::component('category-list', CategoryList::class);
        Livewire::component('brand-list', BrandList::class);
    }
}
