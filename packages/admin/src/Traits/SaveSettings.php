<?php

declare(strict_types=1);

namespace Shopper\Traits;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Shopper\Core\Models\Setting;

trait SaveSettings
{
    /**
     * @param  array<string, mixed>  $keys
     */
    protected function saveSettings(array $keys, bool $locked = true): void
    {
        if ($keys === []) {
            return;
        }

        DB::transaction(function () use ($keys, $locked): void {
            $existing = Setting::query()
                ->whereIn('key', array_keys($keys))
                ->pluck('locked', 'key');

            foreach ($keys as $key => $value) {
                if (($existing[$key] ?? false) && ! $locked) {
                    continue;
                }

                Setting::query()->updateOrCreate(['key' => $key], [
                    'value' => $value,
                    'display_name' => Setting::lockedAttributesDisplayName($key),
                    'locked' => $locked,
                ]);

                $this->forgetSettingCache($key);
            }
        });
    }

    private function forgetSettingCache(string $key): void
    {
        Cache::forget('shopper-setting.'.$key);

        if ($key === 'default_currency_id') {
            Cache::forget('shopper-setting.default_currency');
        }
    }
}
