<?php

declare(strict_types=1);

namespace Shopper\Settings\Items;

use BackedEnum;
use Mckenziearts\Icons\Untitledui\Enums\Untitledui;
use Shopper\Enum\SettingGroup;
use Shopper\Settings\Setting;

final class GeneralSetting extends Setting
{
    public function name(): string
    {
        return __('shopper::pages/settings/menu.general');
    }

    public function description(): string
    {
        return __('shopper::pages/settings/menu.general_description');
    }

    public function icon(): BackedEnum
    {
        return Untitledui::Sliders;
    }

    public function url(): string
    {
        return route('shopper.settings.shop');
    }

    public function order(): int
    {
        return 1;
    }

    public function group(): string
    {
        return SettingGroup::Store->value;
    }
}
