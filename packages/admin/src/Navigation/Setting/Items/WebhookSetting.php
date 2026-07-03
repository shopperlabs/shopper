<?php

declare(strict_types=1);

namespace Shopper\Navigation\Setting\Items;

use Shopper\Enum\SettingGroup;
use Shopper\Navigation\Setting\Setting;

final class WebhookSetting extends Setting
{
    public function name(): string
    {
        return __('shopper::pages/settings/menu.webhooks');
    }

    public function description(): string
    {
        return __('shopper::pages/settings/menu.webhooks_description');
    }

    public function icon(): string
    {
        return 'phosphor-webhooks-logo';
    }

    public function url(): string
    {
        return route('shopper.settings.webhooks');
    }

    public function order(): int
    {
        return 8;
    }

    public function group(): string
    {
        return SettingGroup::Store->value;
    }
}
