<?php

declare(strict_types=1);

namespace Shopper\Settings\Items;

use Shopper\Enum\SettingGroup;
use Shopper\Settings\Setting;

final class CarrierSetting extends Setting
{
    public function name(): string
    {
        return __('shopper::pages/settings/menu.carrier');
    }

    public function description(): string
    {
        return __('shopper::pages/settings/menu.carrier_description');
    }

    public function icon(): string
    {
        return 'phosphor-truck-trailer';
    }

    public function url(): string
    {
        return route('shopper.settings.carriers');
    }

    public function order(): int
    {
        return 5;
    }

    public function group(): string
    {
        return SettingGroup::Shipping->value;
    }
}
