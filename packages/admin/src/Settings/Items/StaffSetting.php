<?php

declare(strict_types=1);

namespace Shopper\Settings\Items;

use Shopper\Settings\Setting;

final class StaffSetting extends Setting
{
    public function name(): string
    {
        return __('shopper::pages/settings/menu.staff');
    }

    public function description(): string
    {
        return __('shopper::pages/settings/menu.staff_description');
    }

    public function icon(): string
    {
        return 'untitledui-shield-separator';
    }

    public function url(): string
    {
        return route('shopper.settings.users');
    }

    public function order(): int
    {
        return 2;
    }
}
