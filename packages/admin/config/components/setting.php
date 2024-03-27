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
        'general' => Pages\Settings\General::class,
        'inventory-index' => Pages\Settings\Inventories\Browse::class,
        'inventory-create' => Pages\Settings\Inventories\Create::class,
        'inventory-edit' => Pages\Settings\Inventories\Edit::class,
        'legal' => Pages\Settings\LegalPage::class,
        'analytics' => Pages\Settings\Analytics::class,
        'payment' => Pages\Settings\Payment::class,
        'team-index' => Pages\Settings\Team\Index::class,
        'team-roles' => Pages\Settings\Team\RolePermission::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Livewire Components
    |--------------------------------------------------------------------------
    */

    'components' => [
        'modals.create-permission' => Livewire\Modals\CreatePermission::class,
        'modals.create-role' => Livewire\Modals\CreateRole::class,
        'modals.confirm-password' => Livewire\Modals\ConfirmPassword::class,
        'modals.delete-inventory' => Livewire\Modals\DeleteInventory::class,
        'modals.delete-role' => Livewire\Modals\DeleteRole::class,
        'modals.logout-others-browser' => Livewire\Modals\LogoutOthersBrowser::class,
        'modals.payment-method-form' => Livewire\Modals\PaymentMethodForm::class,

        'settings.inventories.form' => Components\Settings\Inventories\InventoryForm::class,
        'settings.legal.privacy' => Components\Settings\Legal\Privacy::class,
        'settings.legal.refund' => Components\Settings\Legal\Refund::class,
        'settings.legal.shipping' => Components\Settings\Legal\Shipping::class,
        'settings.legal.terms' => Components\Settings\Legal\Terms::class,
        'settings.legal.form' => Components\Settings\Legal\PolicyForm::class,
        'settings.team.permissions' => Components\Settings\Team\Permissions::class,
        'settings.team.users' => Components\Settings\Team\UsersRole::class,

        'slide-overs.create-team-member' => Livewire\SlideOvers\CreateTeamMember::class,
    ],

];
