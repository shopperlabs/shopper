<?php

use Shopper\Framework\Http\Livewire;

return [

    /*
    |--------------------------------------------------------------------------
    | Blade Components
    |--------------------------------------------------------------------------
    |
    | List with Shopper components.
    | Change the alias to call the component with a different name.
    | Extend the component and replace your changes in this file.
    | Remove the component from this file if you don't want to use.
    |
    */

    'blade' => [
        //
    ],

    /*
    |--------------------------------------------------------------------------
    | Livewire Components
    |--------------------------------------------------------------------------
    |
    | Below you reference all the Livewire components that should be loaded
    | for your app. By default all components from Shopper Kit are loaded in.
    |
    */

    'livewire' => [
        'account.devices' => Livewire\Account\Devices::class,
        'account.dropdown' => Livewire\Account\Dropdown::class,
        'account.password' => Livewire\Account\Password::class,
        'account.profile' => Livewire\Account\Profile::class,
        'account.two-factor' => Livewire\Account\TwoFactor::class,

        'brands.browse' => Livewire\Brands\Browse::class,
        'brands.create' => Livewire\Brands\Create::class,
        'brands.edit' => Livewire\Brands\Edit::class,

        'categories.browse' => Livewire\Categories\Browse::class,
        'categories.create' => Livewire\Categories\Create::class,
        'categories.edit' => Livewire\Categories\Edit::class,

        'collections.browse' => Livewire\Collections\Browse::class,
        'collections.create' => Livewire\Collections\Create::class,
        'collections.edit' => Livewire\Collections\Edit::class,
        'collections.products' => Livewire\Collections\Products::class,

        'customers.addresses' => Livewire\Customers\Addresses::class,
        'customers.browse' => Livewire\Customers\Browse::class,
        'customers.create' => Livewire\Customers\Create::class,
        'customers.orders' => Livewire\Customers\Orders::class,
        'customers.profile' => Livewire\Customers\Profile::class,
        'customers.show' => Livewire\Customers\Show::class,

        'dashboard' => Livewire\Dashboard::class,

        'discounts.browse' => Livewire\Discounts\Browse::class,
        'discounts.create' => Livewire\Discounts\Create::class,
        'discounts.edit' => Livewire\Discounts\Edit::class,

        'forms.trix' => Livewire\Forms\Trix::class,
        'forms.uploads.multiple' => Livewire\Forms\Uploads\Multiple::class,
        'forms.uploads.single' => Livewire\Forms\Uploads\Single::class,

        'initialization' => Livewire\Initialization::class,

        'modals.add-product-attribute' => Livewire\Modals\AddProductAttribute::class,
        'modals.add-variant' => Livewire\Modals\AddVariant::class,
        'modals.archived-order' => Livewire\Modals\ArchiveOrder::class,
        'modals.choose-skeleton' => Livewire\Modals\ChooseSkeleton::class,
        'modals.create-mailable' => Livewire\Modals\CreateMailable::class,
        'modals.create-payment-method' => Livewire\Modals\CreatePaymentMethod::class,
        'modals.create-permission' => Livewire\Modals\CreatePermission::class,
        'modals.create-role' => Livewire\Modals\CreateRole::class,
        'modals.create-value' => Livewire\Modals\CreateValue::class,
        'modals.delete-customer' => Livewire\Modals\DeleteCustomer::class,
        'modals.delete-discount' => Livewire\Modals\DeleteDiscount::class,
        'modals.delete-inventory' => Livewire\Modals\DeleteInventory::class,
        'modals.delete-mailable' => Livewire\Modals\DeleteMailable::class,
        'modals.delete-product' => Livewire\Modals\DeleteProduct::class,
        'modals.delete-review' => Livewire\Modals\DeleteReview::class,
        'modals.delete-role' => Livewire\Modals\DeleteRole::class,
        'modals.delete-template' => Livewire\Modals\DeleteTemplate::class,
        'modals.discount-products' => Livewire\Modals\DiscountProducts::class,
        'modals.discount-customers' => Livewire\Modals\DiscountCustomers::class,
        'modals.logout-others-browser' => Livewire\Modals\LogoutOthersBrowser::class,
        'modals.products-lists' => Livewire\Modals\ProductsLists::class,
        'modals.re-order-categories' => Livewire\Modals\ReOrderCategories::class,
        'modals.update-payment-method' => Livewire\Modals\UpdatePaymentMethod::class,
        'modals.update-value' => Livewire\Modals\UpdateValue::class,
        'modals.update-variant-stock' => Livewire\Modals\UpdateVariantStock::class,

        'orders.browse' => Livewire\Orders\Browse::class,
        'orders.show' => Livewire\Orders\Show::class,

        'products.browse' => Livewire\Products\Browse::class,
        'products.create' => Livewire\Products\Create::class,
        'products.edit' => Livewire\Products\Edit::class,
        'products.form.attributes' => Livewire\Products\Form\Attributes::class,
        'products.form.edit' => Livewire\Products\Form\Edit::class,
        'products.form.inventory' => Livewire\Products\Form\Inventory::class,
        'products.form.related-products' => Livewire\Products\Form\RelatedProducts::class,
        'products.form.seo' => Livewire\Products\Form\Seo::class,
        'products.form.shipping' => Livewire\Products\Form\Shipping::class,
        'products.form.variants' => Livewire\Products\Form\Variants::class,
        'products.variant' => Livewire\Products\Variant::class,
        'products.variant-stock' => Livewire\Products\VariantStock::class,

        'reviews.browse' => Livewire\Reviews\Browse::class,
        'reviews.show' => Livewire\Reviews\Show::class,

        'settings.analytics' => Livewire\Settings\Analytics::class,
        'settings.attributes.browse' => Livewire\Settings\Attributes\Browse::class,
        'settings.attributes.create' => Livewire\Settings\Attributes\Create::class,
        'settings.attributes.edit' => Livewire\Settings\Attributes\Edit::class,
        'settings.attributes.values' => Livewire\Settings\Attributes\Values::class,
        'settings.inventories.browse' => Livewire\Settings\Inventories\Browse::class,
        'settings.inventories.create' => Livewire\Settings\Inventories\Create::class,
        'settings.inventories.edit' => Livewire\Settings\Inventories\Edit::class,
        'settings.general' => Livewire\Settings\General::class,
        'settings.legal.privacy' => Livewire\Settings\Legal\Privacy::class,
        'settings.legal.refund' => Livewire\Settings\Legal\Refund::class,
        'settings.legal.shipping' => Livewire\Settings\Legal\Shipping::class,
        'settings.legal.terms' => Livewire\Settings\Legal\Terms::class,
        'settings.mails.add-template' => Livewire\Settings\Mails\AddTemplate::class,
        'settings.mails.configuration' => Livewire\Settings\Mails\Configuration::class,
        'settings.mails.mailables' => Livewire\Settings\Mails\Mailables::class,
        'settings.mails.templates' => Livewire\Settings\Mails\Templates::class,
        'settings.management.create-admin-user' => Livewire\Settings\Management\CreateAdminUser::class,
        'settings.management.management' => Livewire\Settings\Management\Management::class,
        'settings.management.permissions' => Livewire\Settings\Management\Permissions::class,
        'settings.management.role' => Livewire\Settings\Management\Role::class,
        'settings.management.users-role' => Livewire\Settings\Management\UsersRole::class,
        'settings.payments.general' => Livewire\Settings\Payments\General::class,
        'settings.payments.stripe' => Livewire\Settings\Payments\Stripe::class,

        'tables.attributes-table' => Livewire\Tables\AttributesTable::class,
        'tables.brands-table' => Livewire\Tables\BrandsTable::class,
        'tables.categories-table' => Livewire\Tables\CategoriesTable::class,
        'tables.collections-table' => Livewire\Tables\CollectionsTable::class,
        'tables.customers-table' => Livewire\Tables\CustomersTable::class,
        'tables.orders-table' => Livewire\Tables\OrdersTable::class,
        'tables.products-table' => Livewire\Tables\ProductsTable::class,
        'tables.reviews-table' => Livewire\Tables\ReviewsTable::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Components Prefix
    |--------------------------------------------------------------------------
    |
    | This value will set a prefix for all Shopper Admin components.
    | By default it's shopper. This is useful if you want to avoid
    | collision with components from other libraries.
    |
    | For example, it's reference components like:
    |
    | <x-shopper-input.textarea />
    | <livewire:shopper-products.create />
    |
    */

    'prefix' => 'shopper',

];
