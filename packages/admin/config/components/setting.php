<?php

declare(strict_types=1);

use Shopper\Livewire;
use Shopper\Livewire\Components;
use Shopper\Livewire\Pages;

return [

    /*
    |--------------------------------------------------------------------------
    | Livewire Pages
    |--------------------------------------------------------------------------
    */

    'pages' => [
        'index' => Pages\Settings\Index::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Livewire Components
    |--------------------------------------------------------------------------
    */

    'components' => [
        'modals.create-payment-method' => Livewire\Modals\CreatePaymentMethod::class,
        'modals.create-permission' => Livewire\Modals\CreatePermission::class,
        'modals.create-role' => Livewire\Modals\CreateRole::class,
        'modals.confirm-password' => Livewire\Modals\ConfirmPassword::class,
        'modals.delete-inventory' => Livewire\Modals\DeleteInventory::class,
        'modals.delete-role' => Livewire\Modals\DeleteRole::class,
        'modals.logout-others-browser' => Livewire\Modals\LogoutOthersBrowser::class,
        'modals.update-payment-method' => Livewire\Modals\UpdatePaymentMethod::class,

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
    ],

];
