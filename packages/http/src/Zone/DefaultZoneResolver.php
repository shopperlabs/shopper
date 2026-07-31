<?php

declare(strict_types=1);

namespace Shopper\Http\Zone;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Shopper\Core\Models\Contracts\Zone as ZoneContract;
use Shopper\Core\Models\Zone;
use Shopper\Http\Contracts\ZoneResolver;

final class DefaultZoneResolver implements ZoneResolver
{
    /**
     * The header value is attacker-controlled: hashing keeps the cache key at
     * a fixed length and free of characters a store could reject, no matter
     * what the client sends.
     */
    public static function cacheKey(string $code): string
    {
        return 'shopper.http.zone.'.hash('xxh128', $code);
    }

    public function resolve(Request $request): ?ZoneContract
    {
        $code = $request->header((string) config('shopper.http.zone.header', 'X-Shopper-Zone'));

        if (filled($code)) {
            $zone = $this->zoneByCode((string) $code);

            if ($zone !== null) {
                return $zone;
            }
        }

        $default = config('shopper.http.zone.default_code');

        if (filled($default)) {
            return $this->zoneByCode((string) $default);
        }

        return null;
    }

    private function zoneByCode(string $code): ?ZoneContract
    {
        if (mb_strlen($code) > 64) {
            return null;
        }

        $ttl = (int) config('shopper.http.zone.cache_ttl', 60);

        $query = fn (): ?Zone => Zone::query()
            ->with('currency')
            ->where('is_enabled', true)
            ->where('code', $code)
            ->first();

        if ($ttl <= 0) {
            return $query();
        }

        return Cache::remember(self::cacheKey($code), $ttl, $query);
    }
}
