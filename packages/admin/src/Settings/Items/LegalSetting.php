<?php

declare(strict_types=1);

namespace Shopper\Settings\Items;

use Shopper\Settings\Setting;

final class LegalSetting extends Setting
{
    public function name(): string
    {
        return __('shopper::pages/settings/menu.legal');
    }

    public function description(): string
    {
        return __('shopper::pages/settings/menu.legal_description');
    }

    public function icon(): string
    {
        return 'untitledui-file-lock-02';
    }

    public function url(): string
    {
        return route('shopper.settings.legal');
    }

    public function order(): int
    {
        return 6;
    }
}
