<?php

declare(strict_types=1);

namespace Shopper\Settings\Items;

use Shopper\Settings\Setting;

final class LocationSetting extends Setting
{
    public function name(): string
    {
        return __('shopper::pages/settings/menu.location');
    }

    public function description(): string
    {
        return __('shopper::pages/settings/menu.location_description');
    }

    public function icon(): string
    {
        return 'untitledui-marker-pin-flag';
    }

    public function url(): string
    {
        return route('shopper.settings.locations');
    }

    public function order(): int
    {
        return 3;
    }
}
