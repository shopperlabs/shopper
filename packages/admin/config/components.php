<?php

declare(strict_types=1);

use Shopper\Http\Livewire;
use Shopper\Http\Livewire\Components;
use Shopper\Http\Livewire\Pages;

return [

    /*
    |--------------------------------------------------------------------------
    | Livewire Pages Components
    |--------------------------------------------------------------------------
    |
    | Below you reference all the Livewire components that should be loaded
    | for your app. By default all components from Shopper Kit are loaded in.
    |
    */

    'dashboard' => Pages\Dashboard::class,

    /*
    |--------------------------------------------------------------------------
    | Account Livewire Components
    |--------------------------------------------------------------------------
    */

    'account.devices' => Components\Account\Devices::class,
    'account.dropdown' => Components\Account\Dropdown::class,
    'account.password' => Components\Account\Password::class,
    'account.profile' => Components\Account\Profile::class,
    'account.two-factor' => Components\Account\TwoFactor::class,

    /*
    |--------------------------------------------------------------------------
    | Shops Livewire Components
    |--------------------------------------------------------------------------
    */
    'attributes.browse' => Components\Attributes\Browse::class,
    'attributes.create' => Components\Attributes\Create::class,
    'attributes.edit' => Components\Attributes\Edit::class,
    'attributes.values' => Components\Attributes\Values::class,

    'brands.browse' => Components\Brands\Browse::class,
    'brands.create' => Components\Brands\Create::class,
    'brands.edit' => Components\Brands\Edit::class,

    'categories.browse' => Components\Categories\Browse::class,
    'categories.create' => Components\Categories\Create::class,
    'categories.edit' => Components\Categories\Edit::class,

    'collections.browse' => Components\Collections\Browse::class,
    'collections.create' => Components\Collections\Create::class,
    'collections.edit' => Components\Collections\Edit::class,
    'collections.products' => Components\Collections\Products::class,

    'customers.addresses' => Components\Customers\Addresses::class,
    'customers.browse' => Components\Customers\Browse::class,
    'customers.create' => Components\Customers\Create::class,
    'customers.orders' => Components\Customers\Orders::class,
    'customers.profile' => Components\Customers\Profile::class,
    'customers.show' => Components\Customers\Show::class,

    'discounts.browse' => Components\Discounts\Browse::class,
    'discounts.create' => Components\Discounts\Create::class,
    'discounts.edit' => Components\Discounts\Edit::class,

    'orders.browse' => Components\Orders\Browse::class,
    'orders.show' => Components\Orders\Show::class,

    'products.browse' => Components\Products\Browse::class,
    'products.create' => Components\Products\Create::class,
    'products.edit' => Components\Products\Edit::class,
    'products.form.attributes' => Components\Products\Form\Attributes::class,
    'products.form.edit' => Components\Products\Form\Edit::class,
    'products.form.inventory' => Components\Products\Form\Inventory::class,
    'products.form.related-products' => Components\Products\Form\RelatedProducts::class,
    'products.form.seo' => Components\Products\Form\Seo::class,
    'products.form.shipping' => Components\Products\Form\Shipping::class,
    'products.form.variants' => Components\Products\Form\Variants::class,
    'products.variant' => Components\Products\Variant::class,
    'products.variant-stock' => Components\Products\VariantStock::class,

    'reviews.browse' => Components\Reviews\Browse::class,
    'reviews.show' => Components\Reviews\Show::class,

    'search' => Components\Search::class,

    /*
    |--------------------------------------------------------------------------
    | Modals Livewire Components
    |--------------------------------------------------------------------------
    */

    'modals.add-variant' => Livewire\Modals\AddVariant::class,
    'modals.archived-order' => Livewire\Modals\ArchiveOrder::class,
    'modals.create-payment-method' => Livewire\Modals\CreatePaymentMethod::class,
    'modals.create-permission' => Livewire\Modals\CreatePermission::class,
    'modals.create-role' => Livewire\Modals\CreateRole::class,
    'modals.create-value' => Livewire\Modals\CreateValue::class,
    'modals.confirm-password' => Livewire\Modals\ConfirmPassword::class,
    'modals.delete-customer' => Livewire\Modals\DeleteCustomer::class,
    'modals.delete-discount' => Livewire\Modals\DeleteDiscount::class,
    'modals.delete-inventory' => Livewire\Modals\DeleteInventory::class,
    'modals.delete-product' => Livewire\Modals\DeleteProduct::class,
    'modals.delete-review' => Livewire\Modals\DeleteReview::class,
    'modals.delete-role' => Livewire\Modals\DeleteRole::class,
    'modals.discount-products' => Livewire\Modals\DiscountProducts::class,
    'modals.discount-customers' => Livewire\Modals\DiscountCustomers::class,
    'modals.logout-others-browser' => Livewire\Modals\LogoutOthersBrowser::class,
    'modals.products-lists' => Livewire\Modals\ProductsLists::class,
    'modals.re-order-categories' => Livewire\Modals\ReOrderCategories::class,
    'modals.related-list' => Livewire\Modals\RelatedList::class,
    'modals.update-payment-method' => Livewire\Modals\UpdatePaymentMethod::class,
    'modals.update-value' => Livewire\Modals\UpdateValue::class,
    'modals.update-variant-stock' => Livewire\Modals\UpdateVariantStock::class,

    /*
    |--------------------------------------------------------------------------
    | Settings Livewire Components
    |--------------------------------------------------------------------------
    */

    'settings.analytics' => Components\Settings\Analytics::class,
    'settings.inventories.browse' => Components\Settings\Inventories\Browse::class,
    'settings.inventories.create' => Components\Settings\Inventories\Create::class,
    'settings.inventories.edit' => Components\Settings\Inventories\Edit::class,
    'settings.general' => Components\Settings\General::class,
    'settings.legal.privacy' => Components\Settings\Legal\Privacy::class,
    'settings.legal.refund' => Components\Settings\Legal\Refund::class,
    'settings.legal.shipping' => Components\Settings\Legal\Shipping::class,
    'settings.legal.terms' => Components\Settings\Legal\Terms::class,
    'settings.management.create-admin-user' => Components\Settings\Management\CreateAdminUser::class,
    'settings.management.management' => Components\Settings\Management\Management::class,
    'settings.management.permissions' => Components\Settings\Management\Permissions::class,
    'settings.management.role' => Components\Settings\Management\Role::class,
    'settings.management.users-role' => Components\Settings\Management\UsersRole::class,
    'settings.payments.general' => Components\Settings\Payments\General::class,

    /*
    |--------------------------------------------------------------------------
    | Tables Components
    |--------------------------------------------------------------------------
    */

    'tables.attributes-table' => Livewire\Tables\AttributesTable::class,
    'tables.brands-table' => Livewire\Tables\BrandsTable::class,
    'tables.categories-table' => Livewire\Tables\CategoriesTable::class,
    'tables.collections-table' => Livewire\Tables\CollectionsTable::class,
    'tables.customers-table' => Livewire\Tables\CustomersTable::class,
    'tables.orders-table' => Livewire\Tables\OrdersTable::class,
    'tables.products-table' => Livewire\Tables\ProductsTable::class,
    'tables.reviews-table' => Livewire\Tables\ReviewsTable::class,

];
