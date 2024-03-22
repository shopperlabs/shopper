<?php

declare(strict_types=1);

namespace Shopper\Sidebar\Infrastructure;

use Illuminate\Contracts\Cache\Repository as Cache;

final class StaticSidebarFlusher implements SidebarFlusher
{
    public function __construct(protected Cache $cache)
    {
    }

    public function flush(string $name): void
    {
        $this->cache->forget(CacheKey::get($name));
    }
}
