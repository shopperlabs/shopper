<?php

declare(strict_types=1);

namespace Shopper\Traits;

use Illuminate\Support\Facades\Cache;
use Shopper\Core\Models\Setting;

trait SaveSettings
{
    public function saveSettings(array $keys): void
    {
        foreach ($keys as $key => $value) {
            Cache::forget('shopper-setting.' . $key);

            Setting::query()->updateOrCreate(['key' => $key], [
                'value' => $value,
                'display_name' => Setting::lockedAttributesDisplayName($key),
                'locked' => true,
            ]);
        }
    }
}
