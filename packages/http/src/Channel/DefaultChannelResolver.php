<?php

declare(strict_types=1);

namespace Shopper\Http\Channel;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Shopper\Core\Models\Contracts\Channel as ChannelContract;
use Shopper\Http\Contracts\ChannelResolver;

final class DefaultChannelResolver implements ChannelResolver
{
    /**
     * The header value is attacker-controlled: hashing keeps the cache key at
     * a fixed length and free of characters a store could reject, no matter
     * what the client sends.
     */
    public static function cacheKey(string $slug): string
    {
        return 'shopper.http.channel.'.hash('xxh128', $slug);
    }

    public function resolve(Request $request): ?ChannelContract
    {
        $slug = $request->header((string) config('shopper.http.channel.header', 'X-Shopper-Channel'));

        if (filled($slug)) {
            $channel = $this->channelBySlug((string) $slug);

            if ($channel !== null) {
                return $channel;
            }
        }

        $default = config('shopper.http.channel.default_slug');

        if (filled($default)) {
            return $this->channelBySlug((string) $default);
        }

        return null;
    }

    private function channelBySlug(string $slug): ?ChannelContract
    {
        if (mb_strlen($slug) > 64) {
            return null;
        }

        $ttl = (int) config('shopper.http.channel.cache_ttl', 60);

        $query = fn (): ?ChannelContract => resolve(ChannelContract::class)::query()
            ->where('is_enabled', true)
            ->where('slug', $slug)
            ->first();

        if ($ttl <= 0) {
            return $query();
        }

        return Cache::remember(self::cacheKey($slug), $ttl, $query);
    }
}
