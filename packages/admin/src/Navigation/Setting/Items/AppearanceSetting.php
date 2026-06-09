<?php

declare(strict_types=1);

namespace Shopper\Navigation\Setting\Items;

use BackedEnum;
use Mckenziearts\Icons\Untitledui\Enums\Untitledui;
use Shopper\Enum\SettingGroup;
use Shopper\Navigation\Setting\Setting;

final class AppearanceSetting extends Setting
{
    public function name(): string
    {
        return __('shopper::pages/settings/menu.appearance');
    }

    public function description(): string
    {
        return __('shopper::pages/settings/menu.appearance_description');
    }

    public function icon(): BackedEnum
    {
        return Untitledui::Palette;
    }

    public function url(): string
    {
        return route('shopper.settings.appearance');
    }

    public function order(): int
    {
        return 2;
    }

    public function group(): string
    {
        return SettingGroup::Store->value;
    }
}
