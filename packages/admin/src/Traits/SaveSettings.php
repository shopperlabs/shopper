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
        DB::transaction(function () use ($keys, $locked): void {
            foreach ($keys as $key => $value) {
                $existingLocked = Setting::query()
                    ->where('key', $key)
                    ->value('locked');

                if ($existingLocked && ! $locked) {
                    continue;
                }

                Cache::forget('shopper-setting.'.$key);

                Setting::query()->updateOrCreate(['key' => $key], [
                    'value' => $value,
                    'display_name' => Setting::lockedAttributesDisplayName($key),
                    'locked' => $locked,
                ]);
            }
        });
    }
}
