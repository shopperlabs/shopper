<?php

declare(strict_types=1);

namespace Shopper\Upgrade\Rector;

/**
 * The 2.x => 3.x class, interface and trait renames that are pure symbol moves,
 * safe to apply mechanically with Rector's RenameClassRector.
 *
 * Behavioral migrations (new contract methods, removed components, money
 * semantics) are intentionally NOT here — those are handled by the Boost
 * upgrade skills, because they require judgment Rector cannot make.
 *
 * Note: Rector renames these symbols everywhere they are referenced (imports,
 * type-hints, `new`, `::class`, `implements`, `use TraitName`, and `extends`
 * of the abstract Setting base). It deliberately leaves `extends` of the now
 * final Setting item classes untouched, since a final class cannot be
 * extended — that case is covered by the settings skill.
 */
final class ClassRenames
{
    /**
     * @var array<string, string>
     */
    public const array MAP = [
        // Settings moved under the Navigation subsystem.
        'Shopper\Settings\Setting' => 'Shopper\Navigation\Setting\Setting',
        'Shopper\Settings\SettingManager' => 'Shopper\Navigation\Setting\SettingManager',
        'Shopper\Settings\Items\CarrierSetting' => 'Shopper\Navigation\Setting\Items\CarrierSetting',
        'Shopper\Settings\Items\CurrencySetting' => 'Shopper\Navigation\Setting\Items\CurrencySetting',
        'Shopper\Settings\Items\GeneralSetting' => 'Shopper\Navigation\Setting\Items\GeneralSetting',
        'Shopper\Settings\Items\LegalSetting' => 'Shopper\Navigation\Setting\Items\LegalSetting',
        'Shopper\Settings\Items\LocationSetting' => 'Shopper\Navigation\Setting\Items\LocationSetting',
        'Shopper\Settings\Items\PaymentSetting' => 'Shopper\Navigation\Setting\Items\PaymentSetting',
        'Shopper\Settings\Items\StaffSetting' => 'Shopper\Navigation\Setting\Items\StaffSetting',
        'Shopper\Settings\Items\TaxSetting' => 'Shopper\Navigation\Setting\Items\TaxSetting',
        'Shopper\Settings\Items\ZoneSetting' => 'Shopper\Navigation\Setting\Items\ZoneSetting',

        // Model contracts relocated across packages.
        'Shopper\Core\Models\Contracts\ShopperUser' => 'Shopper\Models\Contracts\ShopperUser',
        'Shopper\Core\Models\Contracts\Cart' => 'Shopper\Cart\Models\Contracts\Cart',
        'Shopper\Core\Models\Contracts\CartLine' => 'Shopper\Cart\Models\Contracts\CartLine',

        // Two-factor trait split: the old monolith maps to the primary trait.
        // The companion InteractsWithStoreAuthenticationRecovery trait and the
        // matching interfaces are added through the two-factor-auth skill.
        'Shopper\Traits\TwoFactorAuthenticatable' => 'Shopper\Traits\InteractsWithStoreAuthentication',

        // Product edit screens moved from Livewire components to pages.
        'Shopper\Livewire\Components\Products\Form\Edit' => 'Shopper\Livewire\Pages\Product\Overview',
        'Shopper\Livewire\Components\Products\Form\Attributes' => 'Shopper\Livewire\Pages\Product\Attributes',
        'Shopper\Livewire\Components\Products\Form\Files' => 'Shopper\Livewire\Pages\Product\Files',
        'Shopper\Livewire\Components\Products\Form\Inventory' => 'Shopper\Livewire\Pages\Product\Inventory',
        'Shopper\Livewire\Components\Products\Form\Media' => 'Shopper\Livewire\Pages\Product\Media',
        'Shopper\Livewire\Components\Products\Form\RelatedProducts' => 'Shopper\Livewire\Pages\Product\Related',
        'Shopper\Livewire\Components\Products\Form\Seo' => 'Shopper\Livewire\Pages\Product\Seo',
        'Shopper\Livewire\Components\Products\Form\Shipping' => 'Shopper\Livewire\Pages\Product\Shipping',
        'Shopper\Livewire\Components\Products\Form\Variants' => 'Shopper\Livewire\Pages\Product\Variants',
    ];
}
