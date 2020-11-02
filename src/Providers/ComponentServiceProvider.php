<?php

namespace Shopper\Framework\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\View\Compilers\BladeCompiler;
use Livewire\Livewire;
use Shopper\Framework\Http\Livewire\{
    Account\Devices,
    Account\Dropdown,
    Account\Password,
    Account\Profile,
    Account\TwoFactor,
    Brands\Browse as BrowseBrand,
    Brands\Create as CreateBrand,
    Brands\Edit as EditBrand,
    CategoryList,
    CollectionList,
    CustomerList,
    Discount\Create as CreateDiscount,
    Discount\Edit as EditDiscount,
    Discount\DiscountList,
    InventoryHistory,
    NetworkStatus,
    Review\ReviewList,
    Product\Inventory,
    ProductList,
    Settings\Analytics,
    Settings\Payments,
    Settings\Integrations,
    Settings\General,
    Settings\Management\CreateAdminUser,
    Settings\Management\Management,
    Settings\Management\Permissions,
    Settings\Management\Role,
    Settings\Management\UsersRole,
};

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
        $this->callAfterResolving(BladeCompiler::class, function () {
            $this->registerComponent('alert');
            $this->registerComponent('button');
            $this->registerComponent('breadcrumb');
            $this->registerComponent('confirms-password');
            $this->registerComponent('datetime-picker');
            $this->registerComponent('delete-action');
            $this->registerComponent('dialog-modal');
            $this->registerComponent('input.group');
            $this->registerComponent('input.rich-text');
            $this->registerComponent('input.text');
            $this->registerComponent('input.textarea');
            $this->registerComponent('input.file-upload');
            $this->registerComponent('learn-more');
            $this->registerComponent('notify');
        });
    }

    /**
     * Register Livewire components.
     *
     * @return void
     */
    public function registerLivewireComponents()
    {
        Livewire::component('shopper-network-status', NetworkStatus::class);
        Livewire::component('shopper-dropdown', Dropdown::class);
        Livewire::component('shopper-account.profile', Profile::class);
        Livewire::component('shopper-account.password', Password::class);
        Livewire::component('shopper-account.two-factor', TwoFactor::class);
        Livewire::component('shopper-account.devices', Devices::class);

        Livewire::component('shopper-brands-lists', BrowseBrand::class);
        Livewire::component('shopper-brands-create', CreateBrand::class);
        Livewire::component('shopper-brands-edit', EditBrand::class);
        Livewire::component('shopper-category-list', CategoryList::class);
        Livewire::component('shopper-collection-list', CollectionList::class);
        Livewire::component('shopper-product-list', ProductList::class);
        Livewire::component('shopper-customer-list', CustomerList::class);
        Livewire::component('shopper-inventory-history', InventoryHistory::class);
        Livewire::component('shopper-product-inventory', Inventory::class);
        Livewire::component('shopper-reviews-list', ReviewList::class);
        Livewire::component('shopper-discounts-list', DiscountList::class);
        Livewire::component('shopper-create-discount', CreateDiscount::class);
        Livewire::component('shopper-edit-discount', EditDiscount::class);

        /**
         * Settings Components.
         */
        Livewire::component('shopper-settings-management', Management::class);
        Livewire::component('shopper-settings-management-new', CreateAdminUser::class);
        Livewire::component('shopper-settings-management-role', Role::class);
        Livewire::component('shopper-settings-management-permissions', Permissions::class);
        Livewire::component('shopper-settings-management-users-role', UsersRole::class);
        Livewire::component('shopper-settings-analytics', Analytics::class);
        Livewire::component('shopper-settings-payments', Payments::class);
        Livewire::component('shopper-settings-integrations', Integrations::class);
        Livewire::component('shopper-settings-general', General::class);
    }

    /**
     * Register the given component.
     *
     * @param  string  $component
     * @return void
     */
    protected function registerComponent(string $component)
    {
        Blade::component('shopper::components.'.$component, 'shopper-'.$component);
    }
}
