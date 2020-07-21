<?php

namespace Shopper\Framework\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Livewire\Livewire;
use Shopper\Framework\Http\Components\Blade\Breadcrumb;
use Shopper\Framework\Http\Components\Blade\DeleteAction;
use Shopper\Framework\Http\Components\Blade\DateTimePicker;
use Shopper\Framework\Http\Components\Livewire\BrandList;
use Shopper\Framework\Http\Components\Livewire\CategoryList;
use Shopper\Framework\Http\Components\Livewire\CollectionList;
use Shopper\Framework\Http\Components\Livewire\CustomerList;
use Shopper\Framework\Http\Components\Livewire\Product\Inventory;
use Shopper\Framework\Http\Components\Livewire\InventoryHistory;
use Shopper\Framework\Http\Components\Livewire\ProductList;
use Shopper\Framework\Http\Components\Livewire\Review\ReviewList;
use Shopper\Framework\Http\Components\Livewire\User\Dropdown;
use Shopper\Framework\Http\Components\Livewire\User\Profile;

class ComponentServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerLivewireComponents();
        $this->registerBladeComponents();
    }

    /**
     * Register customs Blade Components.
     *
     * @return void
     */
    public function registerBladeComponents()
    {
        Blade::component('datetime-picker', DateTimePicker::class);
        Blade::component('breadcrumb', Breadcrumb::class);
        Blade::component('delete-action', DeleteAction::class);
    }

    /**
     * Register Livewire components.
     *
     * @return void
     */
    public function registerLivewireComponents()
    {
        Livewire::component('category-list', CategoryList::class);
        Livewire::component('brand-list', BrandList::class);
        Livewire::component('collection-list', CollectionList::class);
        Livewire::component('product-list', ProductList::class);
        Livewire::component('customer-list', CustomerList::class);
        Livewire::component('inventory-history', InventoryHistory::class);
        Livewire::component('profile', Profile::class);
        Livewire::component('dropdown', Dropdown::class);
        Livewire::component('product-inventory', Inventory::class);
        Livewire::component('reviews-list', ReviewList::class);
    }
}
