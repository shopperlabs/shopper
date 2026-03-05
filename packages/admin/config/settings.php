<?php

declare(strict_types=1);

use Shopper\Settings\Items;

return [

    /*
    |--------------------------------------------------------------------------
    | Settings Menu
    |--------------------------------------------------------------------------
    |
    | Register setting pages that appear in the admin settings menu.
    | Each setting class must implement \Shopper\Contracts\SettingItem.
    |
    | To create a custom setting, create a class extending \Shopper\Settings\Setting
    | and add it here with true to enable or false to disable.
    |
    */

    'items' => [
        Items\GeneralSetting::class => true,
        Items\StaffSetting::class => true,
        Items\LocationSetting::class => true,
        Items\PaymentSetting::class => true,
        Items\CarrierSetting::class => true,
        Items\LegalSetting::class => true,
        Items\ZoneSetting::class => true,
        Items\TaxSetting::class => true,
        Items\CurrencySetting::class => true,
    ],

];
