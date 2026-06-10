<?php

declare(strict_types=1);

namespace Shopper\Http\Zone;

use Illuminate\Http\Request;
use Shopper\Core\Models\Contracts\Zone as ZoneContract;
use Shopper\Core\Models\Zone;
use Shopper\Http\Contracts\ZoneResolver;

final class DefaultZoneResolver implements ZoneResolver
{
    public function resolve(Request $request): ?ZoneContract
    {
        $code = $request->header((string) config('shopper.http.zone.header', 'X-Shopper-Zone'));

        if (filled($code)) {
            $zone = Zone::query()->where('is_enabled', true)->where('code', $code)->first();

            if ($zone !== null) {
                return $zone;
            }
        }

        $default = config('shopper.http.zone.default_code');

        if (filled($default)) {
            return Zone::query()
                ->where('is_enabled', true)
                ->where('code', $default)
                ->first();
        }

        return null;
    }
}
