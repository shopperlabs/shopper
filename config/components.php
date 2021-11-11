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

        'customers.addresses' => Livewire\Customers\Addresses::class,
        'customers.browse' => Livewire\Customers\Browse::class,
        'customers.create' => Livewire\Customers\Create::class,
        'customers.orders' => Livewire\Customers\Orders::class,
        'customers.profile' => Livewire\Customers\Profile::class,
        'customers.show' => Livewire\Customers\Show::class,

        'discounts.browse' => Livewire\Discounts\Browse::class,
        'discounts.create' => Livewire\Discounts\Create::class,
        'discounts.edit' => Livewire\Discounts\Edit::class,

        'forms.trix' => Livewire\Forms\Trix::class,
        'forms.uploads.multiple' => Livewire\Forms\Uploads\Multiple::class,
        'forms.uploads.single' => Livewire\Forms\Uploads\Single::class,
    ],

];
