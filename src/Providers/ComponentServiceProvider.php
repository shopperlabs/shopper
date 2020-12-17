<?php

namespace Shopper\Framework\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\View\Compilers\BladeCompiler;
use Livewire\Component;
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
    Collections\Browse as BrowseCollections,
    Collections\Create as CreateCollection,
    Collections\Edit as EditCollection,
    Customers\Browse as BrowseCustomers,
    Customers\Create as CreateCustomer,
    Customers\Show as ShowCustomer,
    Customers\Profile as ProfileCustomer,
    Discount\Browse as BrowseDiscounts,
    Discount\Create as CreateDiscount,
    Discount\Edit as EditDiscount,
    InventoryHistory,
    NetworkStatus,
    Reviews\Browse as BrowseReviews,
    Reviews\Show as ShowReview,
    Products\Browse as BrowseProducts,
    Products\Create as CreateProduct,
    Products\Edit as EditProduct,
    Products\Form\Attributes as AttributesFormProduct,
    Products\Form\Edit as EditFormProduct,
    Products\Form\Seo as SEOFormProduct,
    Products\Form\Shipping as ShippingFormProduct,
    Products\Form\Inventory as InventoryFormProduct,
    Settings\Analytics,
    Settings\Attributes\Browse as BrowseAttributes,
    Settings\Attributes\Create as CreateAttribute,
    Settings\Attributes\Edit as EditAttribute,
    Settings\Attributes\Values as ValuesAttribute,
    Settings\Integrations,
    Settings\Inventories\Browse as BrowseInventories,
    Settings\Inventories\Create as CreateInventory,
    Settings\Inventories\Edit as EditInventory,
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

        Component::macro('notify', function ($params) {
            $this->dispatchBrowserEvent('notify', $params);
        });
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
            $this->registerComponent('application-logo');
            $this->registerComponent('button');
            $this->registerComponent('breadcrumb');
            $this->registerComponent('confirms-password');
            $this->registerComponent('datetime-picker');
            $this->registerComponent('danger-button');
            $this->registerComponent('default-button');
            $this->registerComponent('delete-action');
            $this->registerComponent('dialog-modal');
            $this->registerComponent('empty-state');
            $this->registerComponent('input.drag-upload');
            $this->registerComponent('input.file-upload');
            $this->registerComponent('input.group');
            $this->registerComponent('input.markdown');
            $this->registerComponent('input.rich-text');
            $this->registerComponent('input.search');
            $this->registerComponent('input.text');
            $this->registerComponent('input.textarea');
            $this->registerComponent('label');
            $this->registerComponent('learn-more');
            $this->registerComponent('modal');
            $this->registerComponent('notify');
            $this->registerComponent('validation-errors');
            $this->registerComponent('wip');
            $this->registerComponent('wip-placeholder');
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
        Livewire::component('shopper-collections-lists', BrowseCollections::class);
        Livewire::component('shopper-collections-create', CreateCollection::class);
        Livewire::component('shopper-collections-edit', EditCollection::class);
        Livewire::component('shopper-products-browse', BrowseProducts::class);
        Livewire::component('shopper-products-create', CreateProduct::class);
        Livewire::component('shopper-products-edit', EditProduct::class);
        Livewire::component('shopper-products-form-attributes', AttributesFormProduct::class);
        Livewire::component('shopper-products-form-edit', EditFormProduct::class);
        Livewire::component('shopper-products-form-seo', SEOFormProduct::class);
        Livewire::component('shopper-products-form-shipping', ShippingFormProduct::class);
        Livewire::component('shopper-products-form-inventory', InventoryFormProduct::class);
        Livewire::component('shopper-customers-lists', BrowseCustomers::class);
        Livewire::component('shopper-customers-create', CreateCustomer::class);
        Livewire::component('shopper-customers-show', ShowCustomer::class);
        Livewire::component('shopper-customers-profile', ProfileCustomer::class);
        Livewire::component('shopper-inventory-history', InventoryHistory::class);
        Livewire::component('shopper-reviews-browse', BrowseReviews::class);
        Livewire::component('shopper-reviews-show', ShowReview::class);
        Livewire::component('shopper-discounts-browse', BrowseDiscounts::class);
        Livewire::component('shopper-discounts-create', CreateDiscount::class);
        Livewire::component('shopper-discounts-edit', EditDiscount::class);

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
        Livewire::component('shopper-settings-inventories-browse', BrowseInventories::class);
        Livewire::component('shopper-settings-inventories-create', CreateInventory::class);
        Livewire::component('shopper-settings-inventories-edit', EditInventory::class);
        Livewire::component('shopper-settings-attributes-browse', BrowseAttributes::class);
        Livewire::component('shopper-settings-attributes-create', CreateAttribute::class);
        Livewire::component('shopper-settings-attributes-edit', EditAttribute::class);
        Livewire::component('shopper-settings-attributes-values', ValuesAttribute::class);
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
