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
    Brands\Browse as BrowseBrands,
    Brands\Create as CreateBrand,
    Brands\Edit as EditBrand,
    Categories\Browse as BrowseCategories,
    Categories\Create as CreateCategory,
    Categories\Edit as EditCategory,
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
    Settings\Integrations,
    Settings\General,
    Settings\Management\CreateAdminUser,
    Settings\Management\Management,
    Settings\Management\Permissions,
    Settings\Management\Role,
    Settings\Management\UsersRole,
    Settings\Payments\General as PaymentMethods,
    Settings\Payments\Stripe,
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
            $this->registerComponent('empty-state');
            $this->registerComponent('input.group');
            $this->registerComponent('input.rich-text');
            $this->registerComponent('input.text');
            $this->registerComponent('input.textarea');
            $this->registerComponent('input.file-upload');
            $this->registerComponent('learn-more');
            $this->registerComponent('modal');
            $this->registerComponent('notify');
            $this->registerComponent('wip');
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

        Livewire::component('shopper-brands-lists', BrowseBrands::class);
        Livewire::component('shopper-brands-create', CreateBrand::class);
        Livewire::component('shopper-brands-edit', EditBrand::class);
        Livewire::component('shopper-categories-lists', BrowseCategories::class);
        Livewire::component('shopper-categories-create', CreateCategory::class);
        Livewire::component('shopper-categories-edit', EditCategory::class);
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
        Livewire::component('shopper-settings-payments-general', PaymentMethods::class);
        Livewire::component('shopper-settings-payments-stripe', Stripe::class);
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
