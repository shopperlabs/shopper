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
        'setting-index' => Pages\Settings\Index::class,
        'general' => Pages\Settings\General::class,
        'location-index' => Pages\Settings\Locations\Index::class,
        'location-create' => Pages\Settings\Locations\Create::class,
        'location-edit' => Pages\Settings\Locations\Edit::class,
        'legal' => Pages\Settings\LegalPage::class,
        'payment' => Pages\Settings\Payment::class,
        'team-index' => Pages\Settings\Team\Index::class,
        'team-roles' => Pages\Settings\Team\RolePermission::class,
        'zones' => Pages\Settings\Zones::class,
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
        'modals.logout-others-browser' => Livewire\Modals\LogoutOthersBrowser::class,
        'modals.payment-method-form' => Livewire\Modals\PaymentMethodForm::class,

        'settings.locations.form' => Components\Settings\Locations\InventoryForm::class,
        'settings.legal.privacy' => Components\Settings\Legal\Privacy::class,
        'settings.legal.refund' => Components\Settings\Legal\Refund::class,
        'settings.legal.shipping' => Components\Settings\Legal\Shipping::class,
        'settings.legal.terms' => Components\Settings\Legal\Terms::class,
        'settings.legal.form' => Components\Settings\Legal\PolicyForm::class,
        'settings.team.permissions' => Components\Settings\Team\Permissions::class,
        'settings.team.users' => Components\Settings\Team\UsersRole::class,
        'settings.zones.detail' => Components\Settings\Zones\Detail::class,
        'settings.zones.shipping-options' => Components\Settings\Zones\ZoneShippingOptions::class,

        'slide-overs.create-team-member' => Livewire\SlideOvers\CreateTeamMember::class,
        'slide-overs.shipping-option-form' => Livewire\SlideOvers\ShippingOptionForm::class,
        'slide-overs.zone-form' => Livewire\SlideOvers\ZoneForm::class,
    ],

];
