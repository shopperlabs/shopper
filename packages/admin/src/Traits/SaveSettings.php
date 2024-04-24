<?php

declare(strict_types=1);

namespace Shopper\Traits;

use Shopper\Core\Models\Setting;

trait SaveSettings
{
    public function saveSettings(array $keys): void
    {
        foreach ($keys as $key => $value) {
            Setting::query()->updateOrCreate(['key' => $key], [
                'value' => $value,
                'display_name' => Setting::lockedAttributesDisplayName($key),
                'locked' => true,
            ]);
        }
    }
}
