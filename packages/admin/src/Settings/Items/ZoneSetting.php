<?php

declare(strict_types=1);

namespace Shopper\Settings\Items;

use Shopper\Enum\SettingGroup;
use Shopper\Settings\Setting;

final class ZoneSetting extends Setting
{
    public function name(): string
    {
        return __('shopper::pages/settings/menu.zone');
    }

    public function description(): string
    {
        return __('shopper::pages/settings/menu.zone_description');
    }

    public function icon(): string
    {
        return 'untitledui-globe-05';
    }

    public function url(): string
    {
        return route('shopper.settings.zones');
    }

    public function order(): int
    {
        return 7;
    }

    public function group(): string
    {
        return SettingGroup::Shipping->value;
    }
}
